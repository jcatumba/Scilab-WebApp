package MatlabWF;

use strict;
use warnings;

use Apache;
Apache->import();

sub handler {
    my $r = shift;
    $r->content_type('text/plain');
    $r->print('Hello World!');
    return Apache::OK;
}
