#!/bin/bash

echo "Downloading and installing pip"

curl https://bootstrap.pypa.io/get-pip.py -o get-pip.py && python get-pip.py --prefix=$HOME/.local

echo "Downloading and installing awscli"

yes | $HOME/.local/bin/pip install awscli --prefix=$HOME/.local

echo "Fetching oauth private key from cloudcube"
AWS_ACCESS_KEY_ID=$CLOUDCUBE_ACCESS_KEY_ID AWS_SECRET_ACCESS_KEY=$CLOUDCUBE_SECRET_ACCESS_KEY $HOME/.local/bin/aws s3 cp s3://cloud-cube-eu/zrbqx2c35o3p/oauth-private.key storage/oauth-private.key

echo "Fetching oauth public key from cloudcube"
AWS_ACCESS_KEY_ID=$CLOUDCUBE_ACCESS_KEY_ID AWS_SECRET_ACCESS_KEY=$CLOUDCUBE_SECRET_ACCESS_KEY $HOME/.local/bin/aws s3 cp s3://cloud-cube-eu/zrbqx2c35o3p/oauth-public.key storage/oauth-public.key

echo "Finished oauth keys setup"

