# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|
	config.vm.hostname = "DTBot"
	config.vm.box = "ubuntu/trusty64"
#	config.vm.box = "DTBot"
#	config.vm.box_url = "http://files.vagrantup.com/precise32.box"

	config.vm.network :forwarded_port, guest: 5672, host: 5672
	config.vm.network :forwarded_port, guest: 15672, host: 15672

#	config.vm.provision :puppet do |puppet|
#		puppet.manifests_path = "puppet/manifests"
#		puppet.module_path = "puppet/modules"
#	end
#	config.vm.provision "shell", inline: "echo test"

#	config.vm.provision "shell", inline: "echo 'deb http://www.rabbitmq.com/debian/ testing main' | sudo tee /etc/apt/sources.list.d/rabbitmq.list"
#	config.vm.provision "shell", inline: "wget -O- https://www.rabbitmq.com/rabbitmq-release-signing-key.asc | sudo apt-key add -"
#	config.vm.provision "shell", inline: "sudo apt-get update"
	config.vm.provision "shell", inline: "sudo apt-get -y install rabbitmq-server"
  
	config.vm.provider :virtualbox do |v|
		v.name = "DTBot"
	end

end
