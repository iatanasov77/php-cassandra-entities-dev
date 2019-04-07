
sudo yum install gcc-c++ patch readline readline-devel zlib zlib-devel    libyaml-devel libffi-devel openssl-devel make    bzip2 autoconf automake libtool bison iconv-devel sqlite-devel

curl -sSL https://rvm.io/mpapis.asc | gpg2 --import -
curl -sSL https://rvm.io/pkuczynski.asc | gpg2 --import -
curl -L get.rvm.io | bash -s stable

source /home/vagrant/.rvm/scripts/rvm
rvm reload
rvm requirements run

# Install Ruby-2.6
rvm install 2.6 
rvm use 2.6 --default
