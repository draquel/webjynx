<?php 
	//Initialize Database Connection
	$_SESSION['db'] = new Sql();
	$_SESSION['db']->init("localhost","root","Ed17i0n!");
	$_SESSION['db']->connect("DBObj");	
?>
<!-- Page Specific Styles -->
	<style>	 </style>
<!-- Preload CSS Images -->    
    <img class="hidden" src="/img/stock_head1.svg" alt="Header img 1" />
<!--Page Content -->
    <div id="pg" class="container-fluid">
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
            </div>
        </div>
    </div>