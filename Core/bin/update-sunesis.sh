#!/bin/bash
declare app_path=/srv/www/am_common
declare temp_path=`mktemp -d`
declare repos_path=https://svn.perspective-uk.com/apprenticeships/trunk
declare -i revision
declare message

if [ $# -gt 0 ] ; then
        if [ $# -eq 1 ] ; then
                if [[ $1 =~ ^[0-9]+$ ]] ; then
                        revision=$1
                        message="(`date --rfc-3339=seconds`): Revision $revision of $repos_path"
                        echo $message
                        sleep 1
                        svn export --force --revision $revision $repos_path $temp_path
                        if [ $? -gt 0 ] ; then
                                echo "Error exporting repository"
                                rm -rf $temp_path
                                exit 1
                        fi
                else
                        repos_path=$1
                        revision=`svn info $repos_path | grep "Revision:" | cut -d' ' -f2`
                        message="(`date --rfc-3339=seconds`): Revision HEAD($revision) of $repos_path"
                        echo $message
                        sleep 1
                        svn export --force --revision HEAD $repos_path $temp_path
                        if [ $? -gt 0 ] ; then
                                echo "Error exporting repository"
                                rm -rf $temp_path
                                exit 1
                        fi
                fi
        else
                message="(`date --rfc-3339=seconds`): svn export --force $@ $temp_path"
                echo $message
                sleep 1
                svn export --force $@ $temp_path
                if [ $? -gt 0 ] ; then
                        echo "Error exporting repository"
                        rm -rf $temp_path
                        exit 1
                fi
        fi
else
        revision=`svn info $repos_path | grep "Revision:" | cut -d' ' -f2`
        message="(`date --rfc-3339=seconds`): Revision HEAD($revision) of $repos_path"
        echo $message
        sleep 1
        svn export --force --revision HEAD $repos_path $temp_path
        if [ $? -gt 0 ] ; then
                echo "Error exporting repository"
                rm -rf $temp_path
                exit 1
        fi
fi


find $temp_path \( -name '*.php' -o -name '*.sh' -o -name '*.sql' \) -exec dos2unix {} \;

echo "Setting file permissions"
chgrp -R webdev $temp_path
find $temp_path -type d -exec chmod g=rwxs,o=rx {} \;
find $temp_path -type f -exec chmod g=rw,o=r {} \;
find $temp_path -name '*.sh' -exec chmod +x {} \;

echo "Renaming ${app_path} to ${app_path}_original"
mv $app_path ${app_path}_original

echo "Moving downloaded files to ${app_path}"
mv $temp_path $app_path

echo "Restoring subversion log file ($app_path/svn.log)"
touch ${app_path}_original/svn.log # Create log if it does not yet exist
mv ${app_path}_original/svn.log $app_path/svn.log

echo "Writing to log"
echo $message >> $app_path/svn.log

echo "Erasing ${app_path}_original"
rm -rf ${app_path}_original

echo "Creating symbolic link to ${app_path}_data/uploads"
ln -s ${app_path}_data/uploads ${app_path}/uploads
