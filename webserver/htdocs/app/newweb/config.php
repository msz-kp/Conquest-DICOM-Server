<?php
  $folder = 'c:\\dicomserver\\webserver\\htdocs\\app\\newweb';	// where are the newweb files
  $exe    = 'dgate';				// communication with DICOM server
  $quote  = '""';				// quotes in command line

  if (PHP_OS_FAMILY != 'Windows') {		// On Linux:
    $exe = './' . $exe;				// start as ./servertask
    $quote = '\"';				// quotes in command line
  }

  $userlogin = false;				// uses single file login system
  $wplogin   = false;				// uses wordpress login system
  $cors      = false;				// allow cross site use
