<?php

use Async\Dns\Query;

use PHPUnit\Framework\TestCase;

class DNSTest extends TestCase
{
	/**
	 * @covers \Async\Dns\Query::__construct
	 */
	public function testConstructor()
	{
		$d = new Query("127.0.0.1");
		$this->assertInstanceOf('Async\Dns\Query', $d);
	}

	/**
	 * @covers \Async\Dns\Query::__construct
	 */
	public function testConstructorNoServer()
	{
        $this->expectException(\InvalidArgumentException::class);
		$d = new Query();
    }
    
	/**
	 * @covers \Async\Dns\Result::__construct
	 * @covers \Async\Dns\Query::query
	 * @covers \Async\Dns\Answer::count
	 * @covers \Async\Dns\Result::getData
	 * @covers \Async\Dns\Result::getType
	 * @covers \Async\Dns\Result::getTypeId
	 * @covers \Async\Dns\Result::getString
	 * @covers \Async\Dns\Result::getExtras
	 */
	public function testQueryAndAnswer()
	{
        $dns_server = "8.8.8.8"; // Our DNS Server

        $dns_query = new Query($dns_server); // create  Query object - there are other options we could pass here
		$this->assertInstanceOf('Async\Dns\Query', $dns_query);

        $question = "msn.com"; // the question we will ask
        $type = "A"; // the type of response(s) we want for this question

        $result = $dns_query->query($question, $type); // do the query
		$this->assertInstanceOf('Async\Dns\Answer', $result);

        //Process Results
        $count = $result->count(); // number of results returned
        $this->assertEquals(1, $count);

        foreach ($result as $result_count) {
            // only after A records
            if ($result_count->getType() === "A") {
                $this->assertEquals(1, $result_count->getTypeId());
                $this->assertEquals('13.82.28.61', $result_count->getData());
                $this->assertEquals('msn.com has IPv4 address 13.82.28.61', $result_count->getString());
                $this->assertEquals(1, count($result_count->getExtras()));
            }
        }
    }

	/**
	 * @covers \Async\Dns\Query::setError
	 * @covers \Async\Dns\Query::hasError
	 * @covers \Async\Dns\Query::getLastError
	 */
	public function testQueryAndAnswerErrorServer()
	{
        $dns_server = "127.0.0.1"; // Our DNS Server

        $dns_query = new Query($dns_server);
        $question = "msn.com";
        $type = "A";

        // Trap Errors
		try {
        	$result = $dns_query->query($question, $type); // do the query
		} catch(\Exception $e) {
			$this->assertEquals('Failed to read data buffer', $dns_query->getLastError());
			$this->assertTrue($dns_query->hasError());
		}
	}

	/**
	 * @covers \Async\Dns\Types::getByName
	 */
	public function testQueryAndAnswerErrorType()
	{
        $dns_server = "1.1.1.1"; // Our DNS Server

        $dns_query = new Query($dns_server);
        $question = "msn.com";
        $type = "BAD";

        // Trap Errors
		try {
        	$result = $dns_query->query($question, $type); // do the query
		} catch(\Exception $e) {
			$this->assertEquals('Invalid Query Type BAD', $dns_query->getLastError());
		}
	}

	public function testQueryAndAnswerErrorOpen()
	{
        $dns_server = "tcp:://127.1.1.1"; // Our DNS Server

        $dns_query = new Query($dns_server, 53, 5, false);
        $question = "msn.com";
        $type = "A";

        // Trap Errors
		try {
        	$result = $dns_query->query($question, $type); // do the query
		} catch(\Exception $e) {
			$this->assertEquals('Failed to Open Socket', $dns_query->getLastError());
		}
	}
}