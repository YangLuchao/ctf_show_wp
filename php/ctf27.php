<?php
//621022********5237
$myfile = fopen("zid.txt", "w") or die("Unable to open file!");
for ($year = 1990; $year < 1993; $year++) {
    for ($mon = 1; $mon < 10; $mon++) {
        for ($day = 01; $day < 10; $day++) {
            $txt = ('621022' . $year . '0' . $mon . '0' . $day . '5237') . "\n";
            fwrite($myfile, $txt);
        }
    }
}
for ($year = 1990; $year < 1993; $year++){
    for ($mon = 1; $mon < 10; $mon++) {
        for ($day = 10; $day <= 31; $day++) {
            $txt = ('621022' . $year . "0" . $mon . $day . '5237') . "\n";
            fwrite($myfile, $txt);
        }
    }
}
for ($year = 1990;$year < 1993;$year++){
    for ($mon = 10; $mon <= 12; $mon++) {
        for ($day = 10; $day <= 31; $day++) {
            $txt = ('621022' . $year . $mon . $day . '5237') . "\n";
            fwrite($myfile, $txt);
        }
    }
}
for ($year = 1990;$year < 1993;$year++){
    for ($mon = 10; $mon <= 12; $mon++) {
        for ($day = 01; $day < 10; $day++) {
            $txt = ('621022' . $year . $mon . "0" . $day . '5237') . "\n";
            fwrite($myfile, $txt);
        }
    }
}
fclose($myfile);