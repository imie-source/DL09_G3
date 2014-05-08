# execute first apt to run apt-get update
include_recipe "apt"

execute "installing or updating git" do
    command "cd / && apt-get -y install git"
    user "root"
end
execute "installing or updating build essentials (make)" do
    command "cd / && apt-get -y install build-essential"
    user "root"
end
execute "installing or updating pcre" do
    command "cd / && apt-get -y install libpcre3-dev"
    user "root"
end

include_recipe "nginx"
include_recipe "php"
include_recipe "php-fpm"

execute "installing phalcon" do
    extension_dir = command "php-config --extension-dir"
    extension_path = "#{extension_dir}/phalcon.so"
    if File.exist?(extension_path) then
        print "phalcon already installed"
    else
        print "installing phalcon"
        command "cd / && git clone git://github.com/phalcon/cphalcon.git && cd cphalcon/build && ./install && cd / && echo 'extension=phalcon.so' > /etc/php5/conf.d/phalcon.ini"
        user "root"
    end
end

include_recipe "mongodb"
php_pear "mongo" do
  action :install
end

execute "restoring mongodb" do
    command "cd /var/www/spasm/install/mongo/ && mongorestore --port #{node['mongodb']['config']['port']}"
    user "vagrant"
end

execute "restarting services" do
    command "service nginx restart && service mongodb restart && service php5-fpm restart"
    user "root"
end