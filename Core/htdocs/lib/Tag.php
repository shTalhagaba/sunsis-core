<?php
class Tag extends Entity
{
	public static function loadFromDatabase(PDO $link, $id)
	{
		if($id == '')
		{
			return null;
		}
		
		$key = addslashes((string)$id);
		$query = <<<HEREDOC
SELECT
	*
FROM
	tags
WHERE
	id='$key';
HEREDOC;
		$st = $link->query($query);

		$tag = null;
		if($st)
		{
			$tag = null;
			$row = $st->fetch();
			if($row)
			{
				$tag = new Tag();
				$tag->populate($row);
			}
			
		}
		else
		{
			throw new Exception("Could not execute database query to find tag. " . '----' . $query . '----' . $link->errorCode());
		}

		return $tag;	
	}
	
	public function save(PDO $link)
	{
        $this->updated_at = date('Y-m-d H:i:s');
        if($this->id == '')
        {
            $this->created_at = date('Y-m-d H:i:s');
        }

        return DAO::saveObjectToTable($link, 'tags', $this);
	}
	
	public function delete(PDO $link)
	{
		// Placeholder
	}
	
	public function isSafeToDelete(PDO $link)
	{
		return false;
	}

    public static function getTagsForSelectList(PDO $link, $entity, $include_general = true)
    {
        return $include_general ? 
            DAO::getResultset($link, "SELECT id, name, type FROM tags WHERE tags.type IN ('General', '{$entity}') ORDER BY type, name") : 
            DAO::getResultset($link, "SELECT id, name, type FROM tags WHERE tags.type IN ('{$entity}') ORDER BY type, name");
    }

    public $id = null;
    public $name = null;
    public $type = null;
    public $order_column = null;
    public $created_at = null;
    public $updated_at = null;

}
