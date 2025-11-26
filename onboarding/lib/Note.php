<?php
class Note extends Entity
{
    public function __construct()
    {
        $user = $_SESSION['user'];
        $this->username = $user->username;
        $this->firstnames = $user->firstnames;
        $this->surname = $user->surname;
        $this->fqn = $user->getFullyQualifiedName();
        $this->created = ''; // Will write NULL to the database

    }

    public static function loadFromDatabase(PDO $link, $id)
    {
        if($id == '' || !is_numeric($id))
        {
            throw new Exception("Argument id must be numeric");
        }

        $note = null;
        $sql = "SELECT * FROM notes WHERE id=$id";
        $st = $link->query($sql);
        if($st)
        {
            if($row = $st->fetch())
            {
                $note = new Note();
                $note->populate($row);
            }

        }
        else
        {
            throw new DatabaseException($link, $sql);
        }

        return $note;
    }


    public function save(PDO $link)
    {
        if(($this->parent_table == '') || ($this->parent_id == ''))
        {
            throw new Exception("Notes must have a parent table and parent record id");
        }

        // Check authorisation
        $acl = ACL::loadFromDatabase($link, 'note', $this->id);
        if(!$acl->isAuthorised($_SESSION['user'], 'write'))
        {
            throw new UnauthorizedException();
        }

        // Clean note field
        $this->note = preg_replace('/[\n\r]+/', "\n", $this->note); // Remove superfluous newlines
        $this->note = trim(strip_tags($this->note)); // Remove HTML tags

        DAO::saveObjectToTable($link, 'notes', $this);

        return $this->id;
    }


    public function delete(PDO $link)
    {
        // Check authorisation
        $acl = ACL::loadFromDatabase($link, 'note', $this->id);
        if(!$acl->isAuthorised($_SESSION['user'], 'write'))
        {
            throw new UnauthorizedException();
        }

        $sql = <<<HEREDOC
DELETE FROM
	notes, acl
USING
	notes LEFT OUTER JOIN acl
	ON (notes.id = acl.resource_id AND acl.resource_category='note')
WHERE
	notes.id = {$this->id}
HEREDOC;
        DAO::execute($link, $sql);
    }

    /**
     * Determines whether the current user can delete this note
     */
    public function isSafeToDelete(PDO $link)
    {
        if($this->is_audit_note)
        {
            return false;
        }

        // Check authorisation
        $acl = ACL::loadFromDatabase($link, 'note', $this->id);
        return $acl->isAuthorised($_SESSION['user'], 'write');
    }

    public static function getLRSAccessLog(PDO $link, $parent_id = '')
    {
        $parent_id = addslashes($parent_id);

        $sql = <<<HEREDOC
SELECT
	notes.*
FROM
	notes LEFT OUTER JOIN users
	ON notes.username = users.username
WHERE
	notes.parent_table='users' AND notes.parent_id = '$parent_id' AND LOCATE('Web Service', SUBJECT) > 0;
HEREDOC;

        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                echo '<div class="note">';
                echo '<div class="header">';
                echo '<table width="100%"><tr><td align="left">'.htmlspecialchars($row['subject']).'</td><div>';
                echo '<td align="right">';
                echo "<div class=\"author\" >User Accessed: " . $row['firstnames'] . " " . $row['surname'];
                echo '</td></tr></table></div>';

                echo ' (' . date('d/m/Y H:i:s T', strtotime($row['created'])) . ')';
                echo HTML::nl2p(htmlspecialchars($row['note']));
                echo '</div>';
            }
        }
        else
        {
            throw new DatabaseException($link, $sql);
        }
    }

    public static function renderNotes(PDO $link, $parent_table, $parent_id, $order_by = '')
    {
        $key_pt = addslashes($parent_table);
        $key_pid = addslashes($parent_id);
        $user_identities = DAO::pdo_implode($_SESSION['user']->getIdentities());

        if($_SESSION['user']->isAdmin())
        {
            $sql = <<<HEREDOC
SELECT
	notes.*,
	users.work_email,
	users.work_telephone
FROM
	notes LEFT OUTER JOIN users
	ON notes.username = users.username
WHERE
	notes.parent_table='$key_pt' AND notes.parent_id='$key_pid'
$order_by
;
HEREDOC;
        }
        else
        {
            $sql = <<<HEREDOC
SELECT
	notes.*,
	GROUP_CONCAT(acl.privilege) AS privileges,
	users.work_email,
	users.work_telephone
FROM
	notes LEFT OUTER JOIN acl
	ON (acl.resource_category='note' AND acl.resource_id = notes.id)
	LEFT OUTER JOIN users ON notes.username = users.username
WHERE
	notes.parent_table='$key_pt' AND notes.parent_id='$key_pid'
	AND acl.ident IN ($user_identities)
	AND acl.privilege IN ('read', 'write')
GROUP BY
	notes.id
$order_by
;
HEREDOC;

        }

        $st = $link->query($sql);
        if($st)
        {
            while($row = $st->fetch())
            {
                echo '<div class="note">';
                echo '<div class="header">';
                echo '<table width="100%"><tr><td align="left">'.htmlspecialchars($row['subject']).'</td>';
                echo '<td align="right">';
                if( ($_SESSION['user']->isAdmin() || (strpos($row['privileges'], 'write') !== FALSE))
                    && $row['is_audit_note'] == '0' )
                {
                    echo <<<HEREDOC
<span class="button" onclick="editLessonNote({$row['id']})">Edit</span>
<span class="button" onclick="deleteLessonNote({$row['id']})">Delete</span></td>
HEREDOC;
                }
                echo '</td></tr></table></div>';

                if($row['work_email'] != '')
                {
                    echo "<div class=\"author\" title=\"{$row['firstnames']} {$row['surname']}, Tel: {$row['work_telephone']}\">{$row['firstnames']} {$row['surname']} <a href=\"mailto:{$row['work_email']}\">{$row['fqn']}</a>";
                }
                else
                {
                    echo "<div class=\"author\">{$row['firstnames']} {$row['surname']} {$row['fqn']}";
                }
                echo ' (' . date('d/m/Y H:i:s T', strtotime($row['created'])) . ')</div>';
                echo HTML::nl2p(htmlspecialchars($row['note']));
                echo '</div>';
            }
        }
        else
        {
            throw new DatabaseException($link, $sql);
        }

    }


    public $id = NULL;
    public $parent_table = NULL;
    public $parent_id = NULL;

    public $subject = NULL;
    public $note = NULL;

    // Permanent record of the author's details
    // (that will survive even if the user is deleted)
    public $username = NULL;
    public $firstnames = NULL;
    public $surname = NULL;
    public $fqn = NULL;

    public $is_audit_note = NULL;
    public $modified = NULL;
    public $created = NULL;
}


?>
