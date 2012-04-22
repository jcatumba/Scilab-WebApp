#!/usr/bin/perl
# Script Welcome_Page.pl

use CGI::Pretty ’:standard’;
print header,
    start_html(’Vegetables’),
    h1(’Eat your vegetables’),
    o(
        li(’peas’),
        li(’broccoli’)
    ),
    hr,
    end_html;
