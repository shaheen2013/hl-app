---
title: README
layout: layout.html
---

# Install packages

In order to install the packages, you need to be executing an older version of Node.

The recommended version (and maximum acceptable) in order to perform an `npm i` is the **10.16.0**

You can change the node version easily with a Node Version Manager such as [`nvm`](https://github.com/nvm-sh/nvm) or [`n`](https://www.npmjs.com/package/n).

## NVM

First you need to install the version 10.16.0

`nvm i 10.16.0`

And then use it

`nvm use 10.16.0`

Finally, to revert to the latest version

`nvm use --lts` 

## N

To install the specific version

`n 10.16.0`

And it's assigned automatically as your node version

To revert to latest

`n lts`