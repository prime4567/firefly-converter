<?php
    if (count($argv)< 3) {
        print "Usage: php bca2csv.php <export-file> <year>\n";
        print "<export-file> = Monthly export from klikbca.com\n";
        print "<year> = Year of the file\n";
    }

    $bcafile = $argv[1];
    $transactionyear = $argv[2];

    fwrite(STDERR, "Reading file $bcafile\n");
    $handle = fopen($bcafile, 'r') or die ("Error: Unable to read file $bcafile\n");

    // Print CSV Header
    print ("date,description,amount\n");

    while ($bcaline = fgets($handle)) {
        // Process only line with transaction
        if (preg_match('/^\'/', $bcaline)) {
            $bcapart = explode(',', $bcaline);

            // Process Date
            preg_match('/^\'(\d{2})\/(\d{2})/', $bcapart[0], $datepart);
            $csvdate = "$datepart[1]/$datepart[2]/$transactionyear";

            // Process Description
            $csvdescription = $bcapart[1];

            // Process Amount
            if ($bcapart[4] == 'DB') $csvamount = "-$bcapart[3]";
            else $csvamount = "$bcapart[3]";

            // Print CSV output
            print ("$csvdate,$csvdescription,$csvamount\n");
        }
    }
?>