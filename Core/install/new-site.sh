#!/bin/bash
PATH=$PATH:/usr/local/bin

declare SCRIPTPATH="${0}"
declare RUNDIRECTORY="${0%%/*}"
declare SCRIPTNAME="${0##*/}" 

# Set the working directory to the directory containing the script
cd "$RUNDIRECTORY"

# Sanity checks
if [ -z $SUDO_USER ] ; then
	echo "Error: this command needs to manipulate file ownership and so must be run with sudo"
	exit 1
fi

if [ ! -e $HOME/.my.cnf ] ; then
	echo "Error: your MySQL username and password must be set in ~/.my.cnf"
	exit 1
fi

if [ ! -e ./apache-template.conf ] ; then
	echo "Error: cannot find the Apache configuration template file (./apache-template.conf)"
	exit 1
fi

# Read command line arguments
case "$#" in
	"1" ) sitename=$1;dbname=$1;username=$1 ;;
	"2" ) sitename=$1;dbname=$2;username=$2 ;;
	"3" ) sitename=$1;dbname=$2;username=$3 ;;
	"*" ) echo "Usage new-site.sh {site-name} [[db_name] [db_user_name]]"; exit 1 ;;
esac

# Clean command line arguments
sitename=${sitename//_/-}
sitename=${sitename// /-}
sitename=${sitename/#am-/}
dirname=am_${sitename//-/_}
dbname=${dbname//-/_}
dbname=${dbname// /_}
username=${username//-/_}
username=${username// /_}

if [[ $dbname != 'am_*' ]] ; then
	dbname=am_$dbname
fi

if [[ $user_name != 'am_*' ]] ; then
	username=am_$username
fi


# Validate command line arguments
if [[ ${#username} -gt 16 ]] ; then
	echo "Error: username $username too long (${#username}). Must be 16 characters or less"
	exit 1
fi

if [ -e /srv/www/$dirname ] ; then
	echo "Error: web directory /srv/www/$dirname already exists"
	exit 1
fi

if [ ! -z `mysql -e 'SHOW DATABASES' | grep $dbname` ] ; then
	echo "Error: MySQL database $dbname already exists"
	exit 1
fi

if [ ! -z `mysql --batch --skip-column-names -e "SELECT user FROM mysql.user WHERE user='$username'"` ] ; then
	echo "Error: MySQL user $username already exists"
	exit 1
fi


# Generate MySQL user password
declare -i day=$RANDOM
declare -i month=$RANDOM
declare -i year=$RANDOM
let "year %= 1000"
let "year += 1000"
let "month %= 11"
let "month += 1"
let "day %= 27"
let "day += 1"
password=`date --date $year-$month-$day +%d%b%Y`


# Display cleaned arguments and prompt user to continue
echo
echo sitename=$sitename
echo dirname=/srv/www/$dirname
echo dbname=$dbname
echo username=$username
echo password=$password

echo
read -n 1 -p "Continue? (Y/N): " retvalue

case "$retvalue" in
	Y | y ) echo; echo; ;;
	* ) echo; echo; exit 0 ;;
esac


# Create directory structure
mkdir -p /srv/www/$dirname/logs
mkdir -p /srv/www/$dirname/backup
mkdir -p /srv/www/$dirname/webdav/default
touch /srv/www/$dirname/webdav/passwords

# Set file permissions
chown -R $SUDO_USER:webdev /srv/www/$dirname
chown apache:webdev /srv/www/$dirname/backup /srv/www/$dirname/webdav/default /srv/www/$dirname/webdav/passwords
chmod -R g+w /srv/www/$dirname


# Create MySQL database
mysql -e "CREATE DATABASE $dbname;
	CREATE USER '$username' IDENTIFIED BY '$password';
	GRANT ALL PRIVILEGES ON $dbname.* TO $username;
	GRANT ALL PRIVILEGES ON central.* TO $username;
	GRANT SELECT ON testdata.* TO $username;
	GRANT SELECT ON lis200809.* TO $username;
	GRANT SELECT ON lad200809.* TO $username;
	GRANT SELECT ON lad20070821.* TO $username;
	GRANT SELECT ON lis0708.* TO $username;
	GRANT SELECT ON lis200910.* TO $username;
	GRANT SELECT ON lad200910.* TO $username;
	GRANT SELECT ON lis201011.* TO $username;
	GRANT SELECT ON lad201011.* TO $username;
	GRANT SELECT on lis201112.* TO $username;
	GRANT SELECT ON lad201112.* TO $username;
	GRANT SELECT ON lis201213.* to $username;
    GRANT SELECT ON lad201213.* to $username;"

# Create Apache config file
cat ./apache-template.conf | sed -e "s/@sitename@/$sitename/g; s/@dirname@/$dirname/g; s/@dbname@/$dbname/g; s/@username@/$username/g; s/@password@/$password/g" > /srv/www/sites.d/$dirname.conf
chown -R $SUDO_USER:webdev /srv/www/sites.d/$dirname.conf
chmod -R g+w /srv/www/sites.d/$dirname.conf

