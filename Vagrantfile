# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANTFILE_API_VERSION = '2'

@script = <<SCRIPT
echo ""
echo ""
echo "               ___               "
echo "              (o,o)              "
echo "             <  .  >             "
echo "              -----              "
echo "+-------------------------------+"
echo "|          IT-AKADEMY           |"
echo "|        VM HACKIT 2020-1       |"
echo "+-------------------------------+"
echo "\n"
echo "Mise à jour des paquets de base du système...\n"
sudo apt-get update &> /dev/null

echo "+--------------------------------------------\n"

echo "Installation d'Apache 2...\n"
sudo apt-get install -y apache2 &> /dev/null
echo "Activation des modules Apache...\n"
sudo a2enmod rewrite headers expires &> /dev/null
echo "Redémarrage d'Apache...\n"
sudo systemctl restart apache2 &> /dev/null

echo "+--------------------------------------------\n"

echo "Installation des utilitaires de base du système...\n"
sudo apt-get install -y zip unzip git-core wget curl ca-certificates apt-transport-https &> /dev/null

echo "+--------------------------------------------\n"

echo "Configuration de SSH\n"
sudo sed -e '/PasswordAuthentication no/ s/^#*/#/' -i /etc/ssh/sshd_config
sudo systemctl restart sshd

echo "+--------------------------------------------\n"

echo "Installation de MariaDb..."
sudo apt-get install -y software-properties-common dirmngr &> /dev/null
export DEBIAN_FRONTEND=noninteractive
sudo debconf-set-selections <<< 'mariadb-server mysql-server/root_password password 0000'
sudo debconf-set-selections <<< 'mariadb-server mysql-server/root_password_again password 0000'
sudo -E apt-get install -y mariadb-server &> /dev/null
sudo mysql -uroot -p0000 -e  "CREATE DATABASE HACKIT;"
sudo mysql -uroot -p0000 -e  "CREATE USER 'hackit' IDENTIFIED BY '0000';"
sudo mysql -uroot -p0000 -e "GRANT USAGE ON *.* TO 'hackit'@localhost IDENTIFIED BY '0000';"
sudo mysql -uroot -p0000 -e "GRANT ALL privileges ON HACKIT.* TO 'hackit'@localhost;"
sudo mysql -uroot -p0000 -e  "FLUSH PRIVILEGES;"
echo "Le mot de passe root de MariaDb est : 0000"
echo "La base de données 'HACKIT' a été créée"
echo "Le mot de passe de l'utilisateur MariaDb 'root' est : 0000 \n"

echo "+--------------------------------------------\n"

echo "Installation de PHP 5.6 et 7...\n"
wget -q https://packages.sury.org/php/apt.gpg -O- | sudo apt-key add - &> /dev/null
echo "deb https://packages.sury.org/php/ stretch main" | sudo tee /etc/apt/sources.list.d/php.list &> /dev/null
sudo apt-get update &> /dev/null
echo "+--------------------------------------------\n"

echo "Installation de PHP5...\n"
sudo apt-get install -y php5.6 php5.6-cli php5.6-dev php5.6-mysql php5.6-common php5.6-mcrypt php5.6-mbstring php5.6-intl php5.6-gd php5.6-dom php5.6-apc php5.6-memcached  php5.6-curl php5.6-zip php5.6-xml php5.6-phpdbg libapache2-mod-php5.6 &> /dev/null
echo "Installation de PHP7...\n"
sudo apt-get install -y php7.2 php7.2-cli php7.2-dev php7.2-mysql php7.2-common php7.2-mbstring php7.2-intl php7.2-gd php7.2-dom php7.2-opcache php7.2-memcached php7.2-curl php7.2-zip php7.2-xml php7.2-phpdbg libapache2-mod-php7.2 &> /dev/null
sudo apt-get install -y php7.3 php7.3-cli php7.3-dev php7.3-mysql php7.3-common php7.3-mbstring php7.3-intl php7.3-gd php7.3-dom php7.3-opcache php7.3-memcached php7.3-curl php7.3-zip php7.3-xml php7.3-phpdbg libapache2-mod-php7.3 &> /dev/null
sudo apt-get install -y php7.4 php7.4-cli php7.4-dev php7.4-mysql php7.4-common php7.4-mbstring php7.4-intl php7.4-gd php7.4-dom php7.4-opcache php7.4-memcached php7.4-curl php7.4-zip php7.4-xml php7.4-phpdbg libapache2-mod-php7.4 &> /dev/null
sudo systemctl restart apache2 &> /dev/null
echo "Activation de PHP7.3...\n"
sudo a2dismod php5.6 php7.2 php7.4 &> /dev/null
sudo a2enmod php7.3 &> /dev/null
sudo update-alternatives --set php /usr/bin/php7.3 &> /dev/null
sudo update-alternatives --set phar /usr/bin/phar7.3 &> /dev/null
sudo update-alternatives --set phar.phar /usr/bin/phar.phar7.3 &> /dev/null
sudo update-alternatives --set phpize /usr/bin/phpize7.3 &> /dev/null
sudo update-alternatives --set php-config /usr/bin/php-config7.3 &> /dev/null
echo "+--------------------------------------------\n"

echo "Installation de Composer...\n"
sudo php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer &> /dev/null
sudo php -r "unlink('composer-setup.php');"
echo "+--------------------------------------------\n"

echo "Installation de NodeJS\n"
sudo curl -sL https://deb.nodesource.com/setup_10.x | sudo -E bash - &> /dev/null
sudo apt install nodejs &> /dev/null
sudo apt install -y build-essential libssl-dev &> /dev/null
sudo npm install --global npm@latest &> /dev/null
sudo npm install --global yarn &> /dev/null
sudo npm install --global gulp-cli &> /dev/null
sudo npm install --global bower &> /dev/null
echo "+--------------------------------------------\n"


echo "Configuration du Vhost Apache...\n"
echo "<VirtualHost *:80>
	DocumentRoot /var/www/html/public

	<Directory /var/www/html/public>
		Options +Indexes +FollowSymLinks
		DirectoryIndex index.php index.html
		Order allow,deny
		Allow from all
		AllowOverride All
	</Directory>

	ErrorLog ${APACHE_LOG_DIR}/error.log
	CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>" > /etc/apache2/sites-available/000-default.conf
echo "Redémarrage d'Apache...\n"
sudo rm -f /var/www/html/index.html
sudo systemctl restart apache2 &> /dev/null

echo "+--------------------------------------------\n"
echo "Configuration de l'environnement de développement"
sudo chgrp -R www-data /var/www/html
sudo sed -ri 's/^(memory_limit = )[0-9]+(M.*)$/\1'256'\2/' /etc/php/7.3/apache2/php.ini
sudo sed -ri 's/error_reporting = E_ALL & ~E_DEPRECATED/error_reporting = E_ALL | E_STRICT/g' /etc/php/7.3/apache2/php.ini
sudo sed -ri 's/display_errors = Off/display_errors = On/g' /etc/php/7.3/apache2/php.ini
sudo systemctl restart apache2 &> /dev/null
echo "+--------------------------------------------\n"

echo "Génération d'une clé SSH"
ssh-keygen -t rsa -q -f "$HOME/.ssh/id_rsa" -N ""
echo "Vous pouvez ajouter la clé suivante à votre dépôt Git\n"
cat $HOME/.ssh/id_rsa.pub

echo "+--------------------------------------------\n"
echo "Installation terminée."
echo "+--------------------------------------------\n"

echo "Configurez votre fichier host du système hôte pour faire pointer psi.primservices.localdomain vers 192.168.33.200"
echo "** Lancez http://192.168.33.200 dans votre navigateur **"
echo "** Lisez README.md pour plus d'informations **"

echo "+--------------------------------------------\n\n"
SCRIPT


Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = 'debian/contrib-stretch64'


	config.vm.box_check_update = true
  config.vm.network "forwarded_port", guest: 22, host: 22229
  config.vm.network "private_network", ip: "192.168.33.200"
	config.vm.synced_folder ".", "/vagrant", disabled: true
  config.vm.synced_folder ".", "/var/www/html", owner: "vagrant", group: "www-data"
  config.vm.provision 'shell', inline: @script

  config.vm.provider "virtualbox" do |vb|
    vb.customize ["modifyvm", :id, "--memory", "4096"]
    vb.customize ["modifyvm", :id, "--name", "HACKIT-CASSEURS"]
		vb.gui = false
  end
end
