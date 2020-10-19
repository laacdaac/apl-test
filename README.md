<h1 align="center">Welcome to APL Technology Aptitude Test 👋</h1>
<p>
  <img alt="Version" src="https://img.shields.io/badge/version-1.0.0-blue.svg?cacheSeconds=2592000" />
</p>

<p> The application will import a large CSV file (100k records) into a queue, where a background worker will process each individual record and updates the database.</p>

<p>Requirements this application fullfills</p>

<p>
    <ul>
        <li>Use Laravel Queue (your choice of queue)></li>
        <li>Use MySQL as the destination database
            <ul>    
                <li>Create a DB migration to setup a Users table</li>
                <li>Use the native Laravel timestamps</li>
                <li>Use the SoftDelete feature</li>
            </ul>
        </li>
        <li>
            <ul>
                <li>Write a script to create a CSV file with 100k records containing a list of users</li>
                <li>Names, emails, passwords and phone numbers (can be fully randomized)</li>
                <li>Include a “deleted” column, with a few records having a value set</li>
            </ul>
        </li>
        <li>Create a CSV file importer, that will accept the generated CSV file and insert the records into the Queue</li>
        <li>Create a Console Command that processes the records in the queue, and update the  database with the data.</li>
        <li>Setup PHP Unit / Functional Tests on the importer and queue worker.</li>
    </ul>
</p>

## Install

<p>This application requires the Laravel Framework 8.x</p>

<p>Get the source code of the application from <a href="https://github.com/laacdaac/apl-test">https://github.com/laacdaac/apl-test</a></p>

<p>In order to run the application in Homestead & VirtualBox VM environment follow the following steps.</p>
<p>Set up a Homestead & VirtualBox VM environment (https://laravel.com/docs/8.x/homestead)</p>

<p>From the project main directory install dependencies required for Homestead</p>

<pre>
    composer install
</pre>

<p>Generate the Homestead config file with the command</p>

<pre>
    php vendor/bin/homestead make
</pre>

<p>Start virtual machine with the command</p>
<pre>
    vagrant up
</pre>

<p>Connect Via SSH to your virtual machine</p>

<pre>
    vagrant ssh
</pre>

<p>Navigate to the code directory code/apl and install Laravel dependencies with</p>
<pre>
    composer install
</pre>

<p>Create your database, or use homestead provided to you by default on your virtual machine</p>

<p>Copy .env.homestead file to .env and set up your environment</p>

<p>Run the database migrations with the command</p>

<pre>
    php artisan migrate
</pre>

<p>Set up the queue of your choice in the .env file of your Laravel project</p>

<p>You can specify defaults for the importer and generator like <b>output_directory</b> in the applications config file config/apl.php</p>

## Usage

<h3>User CSV generator</h3>
<p>Generate users.csv file with the Laravel artisan command</p>

<pre>
    php artisan generatecsv:users {recordcount}
</pre>

<p>The record count to be generated can be set with the {recordcount} argument</p>

<pre>
    php artisan generatecsv:users 100
</pre>

<p>Will generate 100 records into the users.csv file</p>

<p>The file name where the users will be generated can be set with the {filename} argument</p>

<pre>
    php artisan generatecsv:users 100 some_users.csv
</pre>

<p>Will generate 100 records into the some_users.csv file</p>

<p>Use the --with-headers option to generate column name information in the first row of the generated CSV file.</p>

<pre>
    php artisan generatecsv:users 100 some_users.csv --with-headers
</pre>

<h3>User CSV file importer</h3>

<p>Import the users CSV file with the Laravel artisan command</p>

<pre>
    php artisan processcsv:users {filename=users.csv} {--with-headers}
</pre>

<p>The filename defaults to users.csv located in the default storage directory (storage/app). You can specify arbitrary filename as the first argument of the command</p>

<p>Specify the <b>--with-headers</b> option to instruct the importer that the first line of the CSV contains the column mappings</p>

<h3>Process the Queue</h3>

<p>The CSV importer pushes the records into the "users" queue. In order to be processed you have to start Laraval workers with the command</p>

<pre>
    php artisan queue:work --queue=users
</pre>

## Run tests

<p>Before testing, please set the correct database credentials in .env.testing file</p>
<p>In order to test the application navigate to the project code directory "code/apl" and run</p>

<pre>
php artisan test
</pre>

## Author

👤 **Luka László**

* Github: [@laacdaac](https://github.com/laacdaac)
* LinkedIn: [@luka-lászló](https://linkedin.com/in/luka-lászló)
