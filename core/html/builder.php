<?php

namespace core\html;

class builder {
    // page

    /**
     * 
     * @return \core\html\page
     */
    public static function page(): \core\html\page {
        return new \core\html\page();
    }

    // text

    /**
     * 
     * @param string $text
     * @return \core\html\text
     */
    public static function text(string $text): \core\html\text {
        return new \core\html\text($text);
    }

    public static function p(string $text = ""): \core\html\html_element {
        return (new \core\html\html_element("p"))->add_child(builder::text($text));
    }

    public static function em(string $text = ""): \core\html\html_element {
        return (new \core\html\html_element("em"))->add_child(builder::text($text));
    }

    public static function strong(string $text = ""): \core\html\html_element {
        return (new \core\html\html_element("strong"))->add_child(builder::text($text));
    }

    // horisontal rule

    /**
     * 
     * @return \core\html\html_element
     */
    public static function hr(): \core\html\html_element {
        return new \core\html\html_element("hr");
    }

    // links

    /**
     * 
     * @param string $url
     * @param string $text
     * @return \core\html\a
     */
    public static function a(string $url, string $text): \core\html\a {
        return new \core\html\a($url, $text);
    }

    // headers
    public static function h1(string $text): \core\html\html_element {
        return (new \core\html\html_element("h1"))->add_child(builder::text($text));
    }

    public static function h2(string $text): \core\html\html_element {
        return (new \core\html\html_element("h2"))->add_child(builder::text($text));
    }

    public static function h3(string $text): \core\html\html_element {
        return (new \core\html\html_element("h3"))->add_child(builder::text($text));
    }

    public static function h4(string $text): \core\html\html_element {
        return (new \core\html\html_element("h4"))->add_child(builder::text($text));
    }

    public static function h5(string $text): \core\html\html_element {
        return (new \core\html\html_element("h5"))->add_child(builder::text($text));
    }

    public static function h6(): \core\html\html_element {
        return (new \core\html\html_element("h6"))->add_child(builder::text($text));
    }

    // div

    /**
     * 
     * @return \core\html\html_element
     */
    public static function div(): \core\html\html_element {
        return new \core\html\html_element("div");
    }

    //list elements

    /**
     * 
     * @return \core\html\html_element
     */
    public static function ul(): \core\html\html_element {
        return new \core\html\html_element("ul");
    }

    /**
     * 
     * @return \core\html\html_element
     */
    public static function ol(): \core\html\html_element {
        return new \core\html\html_element("ol");
    }

    /**
     * 
     * @return \core\html\html_element
     */
    public static function li(): \core\html\html_element {
        return new \core\html\html_element("li");
    }

    /**
     * 
     * @param array $values
     * @return \core\html\html_element
     */
    public static function ordered_list(array $values): \core\html\html_element {
        $result = self::ol();
        foreach ($values as $value) {

            $result = $result->add_child(builder::li()->add_child($value));
        }
        return $result;
    }

    public static function unordered_list(array $values): \core\html\html_element {
        $result = self::ul();
        foreach ($values as $value) {

            $result = $result->add_child(builder::li()->add_child($value));
        }
        return $result;
    }

    // table elements

    /**
     * 
     * @return \core\html\html_element
     */
    public static function table(): \core\html\html_element {
        return new \core\html\html_element("table");
    }

    /**
     * 
     * @return \core\html\html_element
     */
    public static function td(): \core\html\html_element {
        return new \core\html\html_element("td");
    }

    /**
     * 
     * @return \core\html\html_element
     */
    public static function tr(): \core\html\html_element {
        return new \core\html\html_element("tr");
    }

    public static function th(): \core\html\html_element {
        return new \core\html\html_element("th");
    }

    public static function table_row(array $values): \core\html\html_element {
        $result = self::tr();
        foreach ($values as $value) {
            $result = $result->add_child(builder::td()->add_child(empty($value) ? "" : $value));
        }
        return $result;
    }

    public static function thead(): \core\html\html_element {
        return new \core\html\html_element("thead");
    }

    public static function tfoot(): \core\html\html_element {
        return new \core\html\html_element("tfoot");
    }

    public static function tbody(): \core\html\html_element {
        return new \core\html\html_element("tbody");
    }

    public static function tabular(array $matrix, array $header = []): \core\html\html_element {
        $result = self::table();
        if (!empty($header)) {
            $thead = builder::thead();
            $tr = self::tr();
            foreach ($header as $value) {
                $tr = $tr->add_child(builder::th()->add_child($value));
            }
            $result = $result->add_child($thead->add_child($tr));
        }

        $tbody = self::tbody();
        foreach ($matrix as $value) {
            $tbody = $tbody->add_child(builder::table_row($value));
        }
        $result = $result->add_child($tbody);
        return $result;
    }

    // forms
    public static function form(){
        return new \core\html\form();
    }
    public static function input(string $type = "text"){
        return new \core\html\input($type);
    }
    
    public static function label(string $text, string $for){
        return new \core\html\label($text, $for);
    }
    
    public static function option(string $text, string $value){
        return new \core\html\option($text, $value);
    }
    
    public static function select(array $options, string $selected_key = ""){
        $select = new \core\html\html_element("select");
        $select = $select->add_child(builder::option("Select an option", "NULL"));
        foreach ($options as $key => $text) {
            if(is_numeric($key)){
                $key = $text;
            }
            $option = builder::option($text, $key);
            if($selected_key === $key){
                $option = $option->select();
            }
            $select = $select->add_child($option);
        }
        return $select;
    }
    
}
