<p>Hi Administrator,</p>

<p>
    This is a report for scheduled jobs at <?= date('Y-m-d') ?>:
    <ul>
        <?php 
            $counter = 0;
            foreach($message as $message) {
                $counter++;
                echo('<li>' . $counter . '. ' . $message . '</li>');
            } 
        ?>
    </ul>
</p>

<p>Thank you</p>