# DNS Query API Technical Docs

This documentation covers some (not all) of the objects, methods and
properties of the API. The code itself should be fairly easy to hack if
more complex changes are required.

*Class: Query *

The main DNS API class.

*property* *Query::server* *(string)*
------------------------------------------------------------------------
DNS server hostname or IP as a string

*property* *Query::port* *(decimal)*
------------------------------------------------------------------------
Port to use (default 53)

*property* *Query::timeout* *(decimal)*
------------------------------------------------------------------------
Timeout in seconds (default 60)

*property* *Query::udp* *(bool)*
------------------------------------------------------------------------
Will use UDP if true else use TCP (default true)

*property* *Query::debug* *(bool)*
------------------------------------------------------------------------
Output some debug information when querying (default false)

*property* *Query::debugbinary* *(bool)*
------------------------------------------------------------------------
Dump binary debug output when querying (default false)

*property* *Query::error* *(bool)*
------------------------------------------------------------------------
Set if the last query resulted in an error

*property* *Query::lasterror* *(string)*
------------------------------------------------------------------------
Description of the last error encountered (null string if no error)

*property* *Query::lastnameservers* *(Class::Answer)*
------------------------------------------------------------------------
Nameserver record results returned from the last query

*property* *Query::lastadditional* *(Class::Answer)*
------------------------------------------------------------------------
Result records from the additional section of the last query

*method* *Query::Query* *(hostname (string), [port=53 (decimal)],
[timeout=60 (decimal)], [udp=true (bool)], [debug=false (bool)])*
------------------------------------------------------------------------
The constructor function - do not call this directly, rather use
/$object=new Query(options...)/ to create the object. Configuration
can be changed after initilisation by setting the relevant property
directly.

*mixed* *method* *Query::Query* *(question (string), [type=A (string)])*
------------------------------------------------------------------------
Query the nameserver with the given question for the optionally
specified type (defaults to A). The question string would normally
consist of a hostname, domain name or IP address (in normal or
IN-ADDR.ARPA form).

The query will be executed against the configured server using the set
protocol (UDP if the udp property is true else TCP). If an error is
encountered at any point the error property is set to true, a
description placed in the lasterror property and the method returns false.

On successful completion of the query a Class::Answer is returned
containing the records from the answer portion of the response. The
class properties lastnameservers and lastadditional will be set as
Class::Answer objects containing the resource records returned in the
nameserver and additional portion of the answer respectively.

*string* *method* *Query::SmartALookup* *(hostname (string))*
------------------------------------------------------------------------
Takes a hostname and returns an IP address or a null string if the query
failed. Will recursively lookup aliases to a depth of 5.

Intended as a DNS server specific version of gethostbyname()



*Class: Answer*

*property* *Answer::count* *(decimal)*
------------------------------------------------------------------------
Number of result records contained within the answer

*property* *Answer::results* *(Class::Result Array)*
------------------------------------------------------------------------
An array of Class::Result objects containing each of the result records



*Class: Result*

*property* *Result::type* *(decimal)*
------------------------------------------------------------------------
Decimal record type

*property* *Result::typeid* *(string)*
------------------------------------------------------------------------
String of the type (i.e. A, MX, SOA etc) or null string if an unknown type

*property* *Result::class* *(decimal)*
------------------------------------------------------------------------
Numeric result class type

*property* *Result::ttl* *(decimal)*
------------------------------------------------------------------------
Time-to-live (TTL) of resource record

*property* *Result::data* *(string|binary)*
------------------------------------------------------------------------
The processed output of a known type (the IP address for an A record,
mail exchange for an MX record) or the binary data for an unknown type

*property* *Result::domain* *(string)*
------------------------------------------------------------------------
The domain/domain name/hostname the record applies to i.e. the hostname
that the A record in the data type of an A lookup refers to

*property* *Result::string* *(string)*
------------------------------------------------------------------------
A textual representation of the answer (if the type is known else a null
string) i.e. "www.fish.com has address 1.2.3.4"

*property* *Result::extras* *(string Array)*
------------------------------------------------------------------------
An array of any extra data available for the record if the type was
known with a relevant key i.e. "level" for MX record mail exchange
priorities. See the main documentation for a full list of extra
type-dependent data.
