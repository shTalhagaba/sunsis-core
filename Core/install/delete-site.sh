#!/bin/bash
PATH=$PATH:/usr/local/bin

declare SCRIPTPATH="${0}"
declare RUNDIRECTORY="${0%%/*}"
declare SCRIPTNAME="${0##*/}" 

# Set the working directory to the directory containing the script
cd "$RUNDIRECTORY"

if [ ! -e $HOME/.my.cnf ] ; then
	echo "Error: your MySQL username and password must be set in ~/.my.cnf"
	exit 1
fi

# Read command line arguments
case "$#" in
	"1" ) sitename=$1 ;;
	"*" ) echo "Usage delete-site.sh {site-name}"; exit 1 ;;
esac

# Clean command line arguments
sitename=${sitename//_/-}
sitename=${sitename// /-}
sitename=${sitename/#am-/}
dirname=/srv/www/am_${sitename//-/_}
config=/srv/www/sites.d/am_${sitename//-/_}.conf

# Config file
if [ ! -e $config ] ; then
	echo "Cannot find the Apache file $config - this is required for automatic removal"
	exit 1
fi

declare dbname=`grep PERSPECTIVE_DB_NAME $config | sed -r 's/^.*\b(\w+)\s*$/\1/'`
declare dbuser=`grep 'mysqli.default_user' $config | sed -r 's/^.*\b(\w+)\s*$/\1/'`

echo
echo "Delete web site"
echo "==============="
echo " Apache file: $config"
echo " Web site directory: $dirname"
echo " Database: $dbname"
echo " Database user: $dbuser"

echo
read -n 1 -p "Continue? (Y/N): " retvalue

case "$retvalue" in
	Y | y ) echo; echo; ;;
	* ) echo; echo; exit 0 ;;
esac

if [ -e $dirname ] ; then
	echo "Deleting directory: $dirname"
	rm -rf $dirname
fi

if [ ! -z `mysql --batch --skip-column-names -e "SHOW DATABASES LIKE '$dbname'"` ] ; then
	archive=/srv/www/$dbname`date +%Y%m%dT%H%M%S`.sql.gz
	echo "Archiving $dbname to $archive (please delete this file if not required)"
	mysqldump --single-transaction --routines --order-by-primary $dbname | gzip -c - > $archive
	
	echo "Deleting database: $dbname"
	mysql -e "DROP DATABASE $dbname;"
fi

if [ ! -z `mysql --batch --skip-column-names -e "SELECT user FROM mysql.user WHERE user='$dbuser'"` ] ; then
	echo "Deleting database user: $dbuser"
	mysql -e "DROP USER $dbuser;"
fi

# Delete Apache file last of all (in case of error)
echo "Deleting Apache file: $config"
rm $config
