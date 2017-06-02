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
                <h2>Content</h2>
                <?php
					$content = new Content(4,"Posts");
					$content->dbRead($pdo);
					echo "<p>".var_dump($content->toArray())."</p>";
				?>
                <h2>Post</h2>
                <?php
					$post= new Post(4);
					$post->dbRead($pdo);
					echo "<p>".var_dump($post->toArray())."</p>";
				?>
               <h2>Media</h2>
                <?php
					$media = new Media(19);
					$media->dbRead($pdo);
					echo "<p>".var_dump($media->toArray())."</p>";
				?>
                <h2>Person</h2>
                <?php
					$person = new Person(2,"Contacts");
					$person->dbRead($pdo);
					echo "<p>".var_dump($person->toArray())."</p>";
				?>
                <h2>Contact Info</h2>
                <?php
					$contactInfo = new ContactInfo(0,"Addresses","User");
					$contactInfo->dbRead($pdo);
					echo "<p>".var_dump($contactInfo->toArray())."</p>";
				?>
                <h2>Address</h2>
                <?php
					$address = new Address(0,"User");
					$address->dbRead($pdo);
					echo "<p>".var_dump($address->toArray())."</p>";
				?>
                <h2>Phone</h2>
                <?php
					$phone = new Phone(0,"User");
					$phone->dbRead($pdo);
					echo "<p>".var_dump($phone->toArray())."</p>";
				?>
                <h2>Email</h2>
                <?php
					$email = new Email(3,"User");
					$email->dbRead($pdo);
					echo "<p>".var_dump($email->toArray())."</p>";
				?>
                <h2>Contact</h2>
                <?php
					$contact = new Contact(2,NULL);
					$contact->dbRead($pdo);
					$contact->setContactInfo($pdo);
					echo "<p>".var_dump($contact->toArray())."</p>";
				?>
                <h2>User</h2>
                <pre>
                <?php
					$user = new User(2);
					$user->dbRead($pdo);
					$user->setContactInfo($pdo);
					echo var_dump($user->toArray());
				?>
                </pre>
                <hr>
                
                <h2>Blog</h2>
                <pre>
                <?php
					if(!isset($_SESSION['Blog'])){
						$_SESSION['Blog'] = new Blog(1);
						$_SESSION['Blog']->dbRead($pdo);
						$_SESSION['Blog']->load($pdo);
					}
					echo var_dump($_SESSION['Blog']->toArray());
				?>
                </pre>
                
                <h2>MediaLibrary</h2>
                <pre>
                <?php
					if(!isset($_SESSION['Media'])){
						$_SESSION['Media'] = new MediaLibrary(18);
						$_SESSION['Media']->dbRead($pdo);
						$_SESSION['Media']->load($pdo);
					}
					echo var_dump($_SESSION['Media']->toArray());
				?>
                </pre>
            </div>
        </div>
    </div>