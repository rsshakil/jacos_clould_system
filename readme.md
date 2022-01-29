<p align="left">JCS using Vue</p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About JCS

# php/composer already installed

# git clone
git clone https://repo.dev.jacos.jp/gitbucket/git/y_sakaki/jcs.git

composer install

# make .env
cp .env.example .env
# change APP_ENV in .env
APP_ENV=prod
# edit .env
Change you DB info
# database info

composer update

php artisan key:generate

php artisan migrate --seed

npm install

npm run dev
OR
npm run watch

### postman - newman
```
npm install -g newman
```
#### collection
```
newman run .\tests\postman\JCS.postman_collection.json 
```
#### collection -folder
```
newman run .\tests\postman\JCS.postman_collection.json --folder Local
```
#### collection -folders
```
newman run .\tests\postman\JCS.postman_collection.json --folder Local --folder STEP1 --folder order
```

### About Testcafe

#Ensure that Node.js and npm are installed on your computer and run the following command:
```
npm install -g testcafe
```
#### Run Test
```
testcafe chrome tests/testcafe/order_test.js
```
#### Run Test --live
```
testcafe chrome tests/testcafe/order_test.js --live
```
## For Running the Test

#You can run the test from a command shell by calling a single command where you specify the target browser and file path.

testcafe chrome test.js

N.B: TestCafe automatically opens the chosen browser and starts test execution within it.

##Test Speed

#TestCafe provides the capability to change test speed. 

testcafe chrome test.js --speed 0.1

#testcafe function check Example 

test('Check the page URL', async t => {
    await t
        Function code here 
});

#Testcafe select function checking 
testcafe chrome test.js -t "My Login"
