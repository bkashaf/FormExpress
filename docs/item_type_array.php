<?php
/**
 * This file is provided for documentation purposes
 * It reflects the structure used to define the
 * various HTML field types in the item structure.
 * This is taken directly from the original PostNuke version
 * and is being kept as is for reference.
 */

    //                     boil chkb rad  sell text pass txta subm rese hid
    //sequence             x    x    x    x    x    x    x    x    x    x
    //item_type            x    x    x    x    x    x    x    x    x    x
    //item_name                      x    x    x    x    x    x    x    x    x
    //required                  x    x    x    x    x    x               
    //prompt                    x    x    x    x    x    x    x    x
    //prompt_position           x    x    x    x    x    x    x    x
    //item_value           x    x    x    x                             x
    //default_value             x    x    x    x         x
    //cols                                   . x    x    x
    //rows                                               x
    //max_length                               x    x    x
    //item_attributes           x    x    x    x    x    x    x
    //validation_rule           x    x    x    x    x    x    x
    //multiple                            x
    //active               x    x    x    x    x    x    x    x    x    x
    //relative_position    x    x    x    x    x    x    x    x    x

    $item_type_display = array( 'boilerplate' => array( 'sequence'          => '0'
                                                      , 'item_type'         => '1'
                                                      , 'item_name'              => '0'
                                                      , 'required'          => '0'
                                                      , 'prompt'            => '1'
                                                      , 'prompt_position'   => '1'
                                                      , 'item_value'        => '1'
                                                      , 'default_value'     => '0'
                                                      , 'cols'              => '0'
                                                      , 'rows'              => '0'
                                                      , 'max_length'        => '0'
                                                      , 'item_attributes'        => '1'
                                                      , 'validation_rule'        => '0'
                                                      , 'multiple'          => '0'
                                                      , 'active'            => '1'
                                                      , 'relative_position' => '1'
                                                      )
                              , 'checkbox'    => array( 'sequence'          => '0'
                                                      , 'item_type'         => '1'
                                                      , 'item_name'         => '1'
                                                      , 'required'          => '0'
                                                      , 'prompt'            => '1'
                                                      , 'prompt_position'   => '1'
                                                      , 'item_value'        => '1'
                                                      , 'default_value'     => '1'
                                                      , 'cols'              => '0'
                                                      , 'rows'              => '0'
                                                      , 'max_length'        => '0'
                                                      , 'item_attributes'        => '1'
                                                      , 'validation_rule'        => '1'
                                                      , 'multiple'          => '0'
                                                      , 'active'            => '1'
                                                      , 'relative_position' => '1'
                                                      )
                              , 'radio'       => array( 'sequence'          => '0'
                                                      , 'item_type'         => '1'
                                                      , 'item_name'         => '1'
                                                      , 'required'          => '0'
                                                      , 'prompt'            => '1'
                                                      , 'prompt_position'   => '1'
                                                      , 'item_value'        => '1'
                                                      , 'default_value'     => '1'
                                                      , 'cols'              => '0'
                                                      , 'rows'              => '0'
                                                      , 'max_length'        => '0'
                                                      , 'item_attributes'        => '1'
                                                      , 'validation_rule'        => '0'
                                                      , 'multiple'          => '0'
                                                      , 'active'            => '1'
                                                      , 'relative_position' => '1'
                                                      )
                              , 'selectlist'  => array( 'sequence'          => '0'
                                                      , 'item_type'         => '1'
                                                      , 'item_name'         => '1'
                                                      , 'required'          => '1'
                                                      , 'prompt'            => '1'
                                                      , 'prompt_position'   => '1'
                                                      , 'item_value'        => '1'
                                                      , 'default_value'     => '1'
                                                      , 'cols'              => '0'
                                                      , 'rows'              => '1'
                                                      , 'max_length'        => '0'
                                                      , 'item_attributes'        => '1'
                                                      , 'validation_rule'        => '0'
                                                      , 'multiple'          => '0' //post+pnCleanFromVar issue
                                                      , 'active'            => '1'
                                                      , 'relative_position' => '1'
                                                      )
                              , 'text'        => array( 'sequence'          => '0'
                                                      , 'item_type'         => '1'
                                                      , 'item_name'         => '1'
                                                      , 'required'          => '1'
                                                      , 'prompt'            => '1'
                                                      , 'prompt_position'   => '1'
                                                      , 'item_value'        => '0'
                                                      , 'default_value'     => '1'
                                                      , 'cols'              => '1'
                                                      , 'rows'              => '0'
                                                      , 'max_length'        => '1'
                                                      , 'item_attributes'        => '1'
                                                      , 'validation_rule'        => '1'
                                                      , 'multiple'          => '0'
                                                      , 'active'            => '1'
                                                      , 'relative_position' => '1'
                                                      )
                              , 'password'    => array( 'sequence'          => '0'
                                                      , 'item_type'         => '1'
                                                      , 'item_name'         => '1'
                                                      , 'required'          => '1'
                                                      , 'prompt'            => '1'
                                                      , 'prompt_position'   => '1'
                                                      , 'item_value'        => '0'
                                                      , 'default_value'     => '0'
                                                      , 'cols'              => '1'
                                                      , 'rows'              => '0'
                                                      , 'max_length'        => '1'
                                                      , 'item_attributes'        => '1'
                                                      , 'validation_rule'        => '1'
                                                      , 'multiple'          => '0'
                                                      , 'active'            => '1'
                                                      , 'relative_position' => '1'
                                                      )
                              , 'textarea'    => array( 'sequence'          => '0'
                                                      , 'item_type'         => '1'
                                                      , 'item_name'         => '1'
                                                      , 'required'          => '1'
                                                      , 'prompt'            => '1'
                                                      , 'prompt_position'   => '1'
                                                      , 'item_value'        => '0'
                                                      , 'default_value'     => '1'
                                                      , 'cols'              => '1'
                                                      , 'rows'              => '1'
                                                      , 'max_length'        => '1'
                                                      , 'item_attributes'        => '1'
                                                      , 'validation_rule'        => '1'
                                                      , 'multiple'          => '0'
                                                      , 'active'            => '1'
                                                      , 'relative_position' => '1'
                                                      )
                              , 'submit'      => array( 'sequence'          => '0'
                                                      , 'item_type'         => '1'
                                                      , 'item_name'         => '1'
                                                      , 'required'          => '0'
                                                      , 'prompt'            => '1'
                                                      , 'prompt_position'   => '1'
                                                      , 'item_value'        => '1'
                                                      , 'default_value'     => '0'
                                                      , 'cols'              => '0'
                                                      , 'rows'              => '0'
                                                      , 'max_length'        => '0'
                                                      , 'item_attributes'        => '1'
                                                      , 'validation_rule'        => '0'
                                                      , 'multiple'          => '0'
                                                      , 'active'            => '1'
                                                      , 'relative_position' => '1'
                                                      )
                              , 'reset'       => array( 'sequence'          => '0'
                                                      , 'item_type'         => '1'
                                                      , 'item_name'         => '0'
                                                      , 'required'          => '0'
                                                      , 'prompt'            => '1'
                                                      , 'prompt_position'   => '1'
                                                      , 'item_value'        => '1'
                                                      , 'default_value'     => '0'
                                                      , 'cols'              => '0'
                                                      , 'rows'              => '0'
                                                      , 'max_length'        => '0'
                                                      , 'item_attributes'        => '1'
                                                      , 'validation_rule'        => '0'
                                                      , 'multiple'          => '0'
                                                      , 'active'            => '1'
                                                      , 'relative_position' => '1'
                                                      )
                              , 'button'      => array( 'sequence'          => '0'
                                                      , 'item_type'         => '1'
                                                      , 'item_name'         => '1'
                                                      , 'required'          => '0'
                                                      , 'prompt'            => '1'
                                                      , 'prompt_position'   => '1'
                                                      , 'item_value'        => '1'
                                                      , 'default_value'     => '1'
                                                      , 'cols'              => '0'
                                                      , 'rows'              => '0'
                                                      , 'max_length'        => '0'
                                                      , 'item_attributes'        => '1'
                                                      , 'validation_rule'        => '0'
                                                      , 'multiple'          => '0'
                                                      , 'active'            => '1'
                                                      , 'relative_position' => '1'
                                                      )
                              , 'hidden'      => array( 'sequence'          => '0'
                                                      , 'item_type'         => '1'
                                                      , 'item_name'         => '1'
                                                      , 'required'          => '0'
                                                      , 'prompt'            => '0'
                                                      , 'prompt_position'   => '0'
                                                      , 'item_value'        => '1'
                                                      , 'default_value'     => '0'
                                                      , 'cols'              => '0'
                                                      , 'rows'              => '0'
                                                      , 'max_length'        => '0'
                                                      , 'item_attributes'        => '0'
                                                      , 'validation_rule'        => '0'
                                                      , 'multiple'          => '0'
                                                      , 'active'            => '1'
                                                      , 'relative_position' => '0'
                                                      )
                              , 'groupstart'  => array( 'sequence'          => '0'
                                                      , 'item_type'         => '1'
                                                      , 'item_name'         => '0'
                                                      , 'required'          => '0'
                                                      , 'prompt'            => '1'
                                                      , 'prompt_position'   => '1'
                                                      , 'item_value'        => '1'
                                                      , 'default_value'     => '0'
                                                      , 'cols'              => '0'
                                                      , 'rows'              => '0'
                                                      , 'max_length'        => '0'
                                                      , 'item_attributes'        => '1'
                                                      , 'validation_rule'        => '0'
                                                      , 'multiple'          => '0'
                                                      , 'active'            => '1'
                                                      , 'relative_position' => '1'
                                                      )
                              , 'groupend'    => array( 'sequence'          => '0'
                                                      , 'item_type'         => '1'
                                                      , 'item_name'         => '0'
                                                      , 'required'          => '0'
                                                      , 'prompt'            => '0'
                                                      , 'prompt_position'   => '0'
                                                      , 'item_value'        => '0'
                                                      , 'default_value'     => '0'
                                                      , 'cols'              => '0'
                                                      , 'rows'              => '0'
                                                      , 'max_length'        => '0'
                                                      , 'item_attributes'        => '0'
                                                      , 'validation_rule'        => '0'
                                                      , 'multiple'          => '0'
                                                      , 'active'            => '1'
                                                      , 'relative_position' => '0'
                                                      )
                              );

    
?>
   