#!/usr/bin/perl -w

#use CGI;


my(%Variables); #Iniciamos el hash
#Leemos el query enviado
my $buffer = $ENV{'QUERY_STRING'};

#Dividimos cada variable
my @pairs = split(/&/, $buffer);

foreach my $pair (@pairs) {
#Separamos la variable de su valor
my ($name, $value) = split(/=/, $pair);

#Decodificamos
$name =~ tr/+/ /;
$name =~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C", hex($1))/eg;
$value =~ tr/+/ /;
$value =~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C", hex($1))/eg;

#Asignamos una nueva llave al valor
$Variables{$name} = $value;
}

print "$Variables{'email'}\n"; 
print "$Variables{'nombre'}\n";
