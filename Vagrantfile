VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = "precise64v2"
  config.vm.box_url = "http://files.vagrantup.com/precise64.box"
  config.vm.network "forwarded_port", guest: 80, host: 8080

  config.vm.network "private_network", ip: "192.168.56.101"
  # config.vm.network "public_network"

  config.vm.synced_folder ".", "/var/www/spasm", :nfs => true

  config.vm.provider "virtualbox" do |vb|
    vb.customize ["modifyvm", :id, "--memory", "1024"]
  end
  
  config.vm.provision "chef_solo" do |chef|
    chef.cookbooks_path = "install/chef/cookbooks"
    chef.add_recipe "main"
    chef.log_level = :debug
    chef.json = {
      "mongodb" => {
        "config" => {
          "port" => 32172,
          "auth" => true
        }
      },
      "nginx" => {
        "default_root" => "/var/www/spasm/public",
        "hostname"     => "spasm"
      }
    }
  end
end
