# Calendar Print

A really simple app to create a monthly calendar representation as a pdf, using the FPDF library(http://www.fpdf.org/).

### limitations:

- can only print all calendars at once

- cant print repeating events (only first date)

- no color coding 

- a maximum of 3 events for each day can be printed

### known issues:

- events including a month-break are only printed in the month they are started

- DownloadResponse is throwing exceptions 
