#!/bin/bash

RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
MAGENTA='\033[0;35m'
CYAN='\033[0;36m'
GRAY='\033[0;37m'
NC='\033[0m' # No Color
 Check if user is root
if [[ $EUID -ne 0 ]]; then
   echo "This script must be run as root"
   sleep 1 
   sudo "$0" "$@"
   exit 1
fi


echo "Running as root..."
sleep 1
clear

while true; do
    clear
    echo -e "${YELLOW}+--------------------------------------------------+${NC}"
    echo -e "${YELLOW}|                                                  |${NC}"
    echo -e "${GREEN}|${GREEN} _______    _      _______     ______             ${GREEN}|${NC}"
    echo -e "${BLUE}|${GREEN} |_   __ \  / \    |_   __ \  |   ____ \          ${BLUE}|${NC}"
    echo -e "${BLUE}|${GREEN}  | |__) | / _ \     | |__) | | (___ \_|          ${BLUE}|${NC}"
    echo -e "${BLUE}|${GREEN}  |  ___/ / ___ \    |  __ /   _  ____            ${BLUE}|${NC}"
    echo -e "${BLUE}|${GREEN}  | |_  _/ /   \ \_ _| |  \ \_| \____) |          ${BLUE}|${NC}"
    echo -e "${BLUE}|                                         ver 3.6  |${NC}"
    echo -e "${BLUE}|${NC}                        B Y                       ${BLUE}|${NC}"
    echo -e "${BLUE}|${NC}                A M I R H O S S E I N             ${BLUE}|${NC}"
	echo -e "${BLUE}|${NC}                   C H O G H A E I                ${BLUE}|${NC}"
    echo -e "${BLUE}|            ---------------------------           |${NC}"
    echo -e "${BLUE}|                      ${GREEN}Main Menu${BLUE}                   |${NC}"
    echo -e "${GREEN}|     ---------------------------------------      |${NC}"
    echo -e "${BLUE}|${YELLOW} 1.${NC} ${CYAN}INSTALL ${NC}                                      ${BLUE}|${NC}"
    echo -e "${BLUE}|${YELLOW} 2.${NC} ${RED}QUIT${NC}                                          ${BLUE}|${NC}"
    echo -e "${GREEN}|                                                  |${NC}" 
    echo -e "${YELLOW}|                                                  |${NC}" 
    echo -e "${YELLOW}+--------------------------------------------------+${NC}"
    echo -e ""
    echo -e "${GREEN}Please choose an option:${NC}"
    echo -e ""
    read -p "Enter option number: " choice

    case $choice in
    
        #UPDATE SEVER 
        1)
            echo -e "${GREEN}Started ...${NC}" 
            echo ""
            echo "Please enter MySQL Root Password: "
            read rootpasswd
 	    RESULT=`mysqlshow --user=root --password=${rootpasswd} asterisk| grep -v Wildcard | grep -o asterisk`
            if [ "$RESULT" == "asterisk" ]; then
            echo -e "${GREEN}Password is Correct !${NC}"
     
	    mysql -uroot -p${rootpasswd} -e "CREATE DATABASE callblaster;"
            mysql -uroot -p${rootpasswd} -e "CREATE USER 'callblaster'@'localhost' IDENTIFIED BY 'callblaster';"
            mysql -uroot -p${rootpasswd} -e "GRANT ALL PRIVILEGES ON callblaster.* TO 'callblaster'@'localhost';"

     

	    else

            echo -e "${RED}MySql Password is Incorrect.${NC}"
            exit 1

            fi
 
            sleep 3  
	    
            yum install unzip -y
            sleep 1
            sleep 1
            cd /var/www/html/
            sleep 1
            wget https://raw.githubusercontent.com/amirhosseinchoghaei/Pars/main/pars.zip
            sleep 1
            sleep 1
            unzip pars.zip
            sleep 1
            sleep 1
            sleep 1
            sleep 1
            cd /var/www/html/
            sleep 1
            cd /var/www/html/
            sleep 1
            chmod 777 -R pars/
            sleep 1
            chmod 777 -R pars/
            sleep 1
            chmod 777 -R /var/spool/asterisk
            sleep 1
            chmod 777 -R /var/spool/asterisk
            sleep 1
            cd /etc/asterisk/
            sleep 1
            cd /etc/asterisk/
            echo "[callblaster]" >> extensions.conf
            echo "exten => 333,1,AGI(/var/www/html/pars/callblaster.php)" >> extensions.conf
	    echo " " >> extensions_custom.conf
            echo "[callblaster]" >> extensions_custom.conf
            echo "exten => 333,1,AGI(/var/www/html/pars/callblaster.php)" >> extensions_custom.conf
            sleep 1
            cd /etc/httpd/conf.d/
            sleep 1
            cd /etc/httpd/conf.d/
            sleep 1
            mv /etc/httpd/conf.d/issabel.conf /etc/httpd/conf.d/issabel.conf2
            sleep 1
            mv /etc/httpd/conf.d/elastix.conf /etc/httpd/conf.d/elastix.conf2
            sleep 1
            wget https://raw.githubusercontent.com/amirhosseinchoghaei/Pars/main/issabel.conf
            sleep 1
            wget https://raw.githubusercontent.com/amirhosseinchoghaei/Pars/main/elastix.conf
            sleep 1
            service httpd restart
            sleep 1
            service asterisk restart
            sleep 1
            echo -e "${GREEN}Control pannel URL : [serverip]/pars ${NC}"
            rm -f /var/www/html/pars.zip
            sleep 12
            ;;

        # EXIT
        2)
            echo ""
            echo -e "${RED}Exiting...${NC}"
            echo "Exiting program"
            exit 0
            ;;
         
      esac
done
