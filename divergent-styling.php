<?php
/**
 * Converts setting values with a style attribute to inline styling on the frontend
 */
namespace Classes\Divergent;

// Bail if accessed directly
if ( ! defined( 'ABSPATH' ) ) 
    die;

class Divergent_Styling {
    
    /**
     * Contains our fields that have CSS selectors
     */
    private $fields;
    
    /**
     * Constructor
     *
     * @param array $frames The array with frames
     */
    public function __construct( Array $frames = array() ) {
        $this->frames = $frames;  
        
        // Examine for CSS related functions
        $this->examine();
        
        // Create our output
        $this->output();
        
    }
    
    /**
     * Examines the frames for CSS related suggestions
     */
    private function examine() {

        foreach( $this->frames as $frame => $fieldGroups ) {
            
            foreach( $fieldGroups as $group ) {
                
                foreach( $group['sections'] as $section ) {
                
                    // Loop through our fields and see if some have a CSS target defined
                    foreach( $section['fields'] as $field ) {

                        if( ! isset($field['css']) )
                            continue;

                        // Save the id per group so we can retrieve the values later.
                        $outputField['css']     = $field['css'];
                        $outputField['id']      = $field['id'];
                        $outputField['type']    = $field['type'];
                        
                        $cssFields[]            = $outputField;

                    }
                    
                    // Now, retrieve our values from the database, but only if we have values
                    if( ! isset($this->fields) )
                        continue;
                    
                    // Retrieve options
                    if( $frame == 'options' )
                        $optionValues   = get_option($group['id']);
                    
                    // Retrieve meta values. For now, only supports posts.
                    if( $frame == 'meta' && is_singular() ) {
                        
                        global $post;
                        
                        $metaValues     = get_metadata( $group['type'], $post->ID, $group['id'], true );
                        
                    }
                    
                    // Loop again through our fields and see if we have values. Please note that meta values with the same ID overwrite option values.
                    foreach( $cssFields as $field ) {
                        
                        if( isset( $optionValues[$field['id']] ) && $optionValues[$field['id']] )
                            $field['values'] = $optionValues[$field['id']];
                        
                        if( isset( $metaValues[$field['id']] ) && $metaValues[$field['id']] )
                            $field['values'] = $metaValues[$field['id']];
                        
                        // Now, if the field has values, we add it to the array.
                        if( isset($field['values']) )
                            $this->fields[] = $field;
                        
                    }
                    
                }
                    
            }
            
        }
        
    } 
    
    /**
     * Retrieve values if we have css fields for them.
     */
    private function output() {
        
        // We should have fields with styles
        if( ! isset($this->fields) )
            return;
             
        $style = '';
        
        // Loop through our fields that have CSS attributes and values
        foreach( $this->fields as $field ) {
            $style.= $field['css'] . '{' . $this->formatField( $field['type'], $field['values'] ) . '}';    
        }
        
        $style = $style ? '<style type="text/css">' . $style . '</style>' : '';
        
        echo $style;
        
    }
    
    /**
     * Formats the css based upon a fields type values
     *
     * @param string    $type   The field type
     * @param array     $values The field values
     */
    private function formatField($type, $values) {
        $style = '';
        
        return $style;
    }
    
}