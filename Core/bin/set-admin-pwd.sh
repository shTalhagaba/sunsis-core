#!/bin/bash

if [[ $# -eq 0 ]] ; then
	echo "Usage: reset_admin_pwd.sh [password] {email_address} ..."
	echo "Password should not contain @ character or it will be considered an email address"
	exit 0
fi

if [[ ! $1 =~ @ ]] ; then
	# First argument is not an email address
	password="$1"
else
	# Generate new password
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
fi

# Find all Sunesis webfolder password files and update the perspective password
find /srv/www -wholename '/srv/www/am_*/webdav/passwords' -exec /usr/local/bin/htpasswd -b {} perspective $password \;

# Email support staff
# If only a password is supplied as an argument, no one will be emailed
if [[ ! $1 =~ @ && $# > 1 ]] ; then
	# First argument a password.
	# More than one argument.
	# Supply arguments to mutt as email addresses, skipping first argument
	mutt -s "Sunesis webfolder password" -e "my_hdr Importance: high" -e "my_hdr Sensitivity: Company-Confidential" ${@:2} <<EOF
Username: perspective
Password: $password

The 'perspective' login is for internal use only.
Please see Ian S-S to provision webfolder access for Sunesis customers.

To create a webfolder connection in Vista:
1) Right-click the Network icon in Windows Explorer (or on the Desktop)
2) Select 'Map Network Drive' from context menu
3) Click 'Connect to a website that you can use to store your ....'
4) Click Next until prompted to enter a location
5) Type in the location as https://customersite.sunesis.uk.net/webfolder/
   (e.g. https://landrover.sunesis.uk.net/webfolder/)
6) Continue to end of wizard, entering login details when required
EOF
else
	if [[ $1 =~ @ ]] ; then
		# First argument an email address.
		# Supply all arguments to mutt as email addresses
		mutt -s "Sunesis webfolder password" -e "my_hdr Importance: high" -e "my_hdr Sensitivity: Company-Confidential" $@ <<EOF
Username: perspective
Password: $password

The 'perspective' login is for internal use only.
Please see Ian S-S to provision webfolder access for Sunesis customers.

To create a webfolder connection in Vista:
1) Right-click the Network icon in Windows Explorer (or on the Desktop)
2) Select 'Map Network Drive' from context menu
3) Click 'Connect to a website that you can use to store your ....'
4) Click Next until prompted to enter a location
5) Type in the location as https://customersite.sunesis.uk.net/webfolder/
   (e.g. https://landrover.sunesis.uk.net/webfolder/)
6) Continue to end of wizard, entering login details when required
EOF
	fi
fi
