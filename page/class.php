<?php if(!$_SESSION['db']->connect("DBObj")){	echo "CONNECTION FAILURE <br >"; } ?>
<!-- Page Specific Styles -->
	<style>	#pg > div:nth-child(1){ background-image:url('/img/stock_head2.svg'); } </style>
<!--Page Content -->
    <div id="pg" class="container-fluid">
    	<div class="row blue_bg">
            <div></div>
        </div>
        <div class="row">
            <div class="col-xs-10 col-xs-offset-1 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
                <h1>Class Testing</h1>
                <h2>DBObj</h2>
                <?php 
					$dbobj = new DBObj(1,"Posts");
					$dbobj->dbRead($_SESSION['db']->con("DBObj"));
					echo "<p>".var_dump($dbobj->toArray())."</p>";
				?>
                <h2>Content</h2>
                <?php
					$content = new Content(1,"Posts");
					$content->dbRead($_SESSION['db']->con("DBObj"));
					echo "<p>".var_dump($content->toArray())."</p>";
				?>
                <h2>Post</h2>
                <?php
					$post= new Post(1);
					$post->dbRead($_SESSION['db']->con("DBObj"));
					echo "<p>".var_dump($post->toArray())."</p>";
				?>
                <h2>Person</h2>
                <?php
					$person = new Person(1,"Contacts");
					$person->dbRead($_SESSION['db']->con("DBObj"));
					echo "<p>".var_dump($person->toArray())."</p>";
				?>
                <h2>Contact Info</h2>
                <?php
					$contactInfo = new ContactInfo(1,"Addresses");
					$contactInfo->dbRead($_SESSION['db']->con("DBObj"));
					echo "<p>".var_dump($contactInfo->toArray())."</p>";
				?>
                <h2>Address</h2>
                <?php
					$address = new Address(1);
					$address->dbRead($_SESSION['db']->con("DBObj"));
					echo "<p>".var_dump($address->toArray())."</p>";
				?>
                <h2>Phone</h2>
                <?php
					$phone = new Phone(1);
					$phone->dbRead($_SESSION['db']->con("DBObj"));
					echo "<p>".var_dump($phone->toArray())."</p>";
				?>
                <h2>Email</h2>
                <?php
					$email = new Email(1);
					$email->dbRead($_SESSION['db']->con("DBObj"));
					echo "<p>".var_dump($email->toArray())."</p>";
				?>
                <h2>Contact</h2>
                <?php
					$contact = new Contact(1,NULL);
					$contact->dbRead($_SESSION['db']->con("DBObj"));
					$contact->setContactInfo($_SESSION['db']->con("DBObj"));
					echo "<p>".var_dump($contact->toArray())."</p>";
				?>
                <h2>User</h2>
                <?php
					$user = new User(1);
					$user->dbRead($_SESSION['db']->con("DBObj"));
					$user->setContactInfo($_SESSION['db']->con("DBObj"));
					echo "<p>".var_dump($user->toArray())."</p>";
				?>
                
                <hr>
                
                <h2>Blog</h2>
                <pre>
                <?php
					if(!isset($_SESSION['Blog'])){
						$_SESSION['Blog'] = new Blog(1);
						$_SESSION['Blog']->dbRead($_SESSION['db']->con("DBObj"));
						$_SESSION['Blog']->load($_SESSION['db']->con("DBObj"));
					}
					echo var_dump($_SESSION['Blog']->toArray());
				?>
                </pre>
            </div>
        </div>
    </div>