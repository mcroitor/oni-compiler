<?php

namespace mc;

class TestSuite {
    private $tests = [];
    private string $name; 
    private int $failedTestsCount = 0;

    public function __construct(string $name, array $tests = [])
    {
        $this->name = $name;
        $this->tests = $tests;
    }

    public static function create(string $name) {
        return new TestSuite($name);
    }

    public function getName(){
        return $this->name;
    }

    public function getTests() {
        return $this->tests;
    }

    public function add(Test $test) {
        $tests = $this->getTests();
        $tests[] = $test;
        return new TestSuite($this->getName(), $tests);
    }

    public function run(){
        \mc\Assert::resetCounts();
        \mc\logger::stdout()->info("+++ Run test suite " . $this->getName() . " +++");

        foreach($this->getTests() as $test){
            $fails = \mc\Assert::failed();
            $exceptions = \mc\Assert::excepted();
            $test->run();
            if($fails != \mc\Assert::failed() || $exceptions != \mc\Assert::excepted()){
                ++ $this->failedTestsCount;
            }
        }

        $this->stat();
        \mc\logger::stdout()->info("+++ Done test suite " . $this->getName() . " +++");
    }

    public function stat() {
        \mc\logger::stdout()->info("============== ASSERTS ================");
        \mc\logger::stdout()->info("asserts pass: " . \mc\Assert::passed());
        \mc\logger::stdout()->info("asserts fail: " . \mc\Assert::failed());
        \mc\logger::stdout()->info("asserts excepted: " . \mc\Assert::excepted());
        \mc\logger::stdout()->info("asserts total: " . \mc\Assert::total());
        \mc\logger::stdout()->info("=============== TESTS ================");
        \mc\logger::stdout()->info("tests failed: " . $this->failedTestsCount);
        \mc\logger::stdout()->info("tests total: " . count($this->tests));
    }
}