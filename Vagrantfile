VAGRANTFILE_API_VERSION = "2"

$setupEnvironment = <<-SCRIPT

cd /vagrant

source ./.ci/vagrant_pre_setup.sh

source ./.ci/common_env.sh
source ./.ci/vagrant_env.sh

source ./.ci/setup.sh

source ./.ci/start.sh

SCRIPT

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = "hashicorp/precise64"
  config.vm.boot_timeout = 900

  config.vm.provision "shell", inline: $setupEnvironment
  
  config.vm.provider "virtualbox" do |v|
    v.memory = 2048
  end
  
  if Vagrant.has_plugin?("vagrant-proxyconf")
    config.proxy.http     = "http://192.168.56.250:3128/"
    config.proxy.https    = "http://192.168.56.250:3128/"
    config.proxy.no_proxy = "localhost,127.0.0.1"
  end
end
