#!/bin/bash
PATH=$PATH:/usr/local/bin

for username in $(/usr/local/bin/mysql --batch --skip-column-names -e "SELECT user FROM mysql.user WHERE user LIKE 'am\_%';"); do
	mysql -vve "GRANT ALL PRIVILEGES ON central.* TO $username;
		GRANT ALL PRIVILEGES ON $username.* to $username;
		GRANT SELECT ON testdata.* TO $username;
		GRANT SELECT ON postcodes.* TO $username;
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
done
