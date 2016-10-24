# -*- mode: ruby -*-
# vi: set ft=ruby :

VAGRANTFILE_API_VERSION = "2"
Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  
  # Every Vagrant virtual environment requires a box to build off of.
  config.vm.box = "Gigasavvy/centos7-LAMP"

  # Virtual Box Configuration
  config.vm.provider "virtualbox" do |v|
    v.name = "Vanko Soft Projects"
    v.customize ["modifyvm", :id, "--memory", "1024"]
  end
  
  config.ssh.forward_agent = true

  # Setup the Network , Forwarded Ports
  config.vm.network "private_network", ip: "10.3.3.3"
  config.vm.network "forwarded_port", guest: 80, host: 8080
  
  # Run provision scripts
  config.vm.provision "shell", path: "Vagrant/provision/packages.sh"
  config.vm.provision "shell", path: "Vagrant/provision/httpd_config.sh"
  
  # Running Chefs
  config.vm.provision "chef_solo" do |chef|
    chef.cookbooks_path = "Vagrant/cookbooks"
	chef.add_recipe "virtual_hosts"
  end
end
