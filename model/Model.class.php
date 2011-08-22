<?php

/**
 * An abstract class that provides a __toString function for all classes which
 * extend it.  The __toString() will return a JSON string of the object
 * NOTE: In order for this to work the properties of the extended class must be protected
 * History: 
 *   2011-08-22 14:55:48 - Generated with Genify - Should not be edited manually.
 * @package bookie
 * @subpackage model
 * @author 
 */

//namespace bookie;
 
/*****************************************************************************//**
 * @package bookie
 * @subpackage model
 * @author 
 */
abstract class GenifyModel
{
  // Get the vaules of the object
   public function toArray()
   {
      return($this->processArray(get_object_vars($this)));
   }

	// Get the values of the array
   private function processArray($array)
   {
      foreach($array as $key => $value)
      {
         if (is_object($value))
         {
            if(is_a($value, 'DateTime'))
            {
					// there is no __toString() function for the PHP DateTime object
					// Return the value of the date time as YYYY-MM-DD HH:MM:SS
               $array[$key] = $value->format('Y-m-d H:i:s');
            }
            else
            {
               $array[$key] = $value->toArray();
            }
         }
         if (is_array($value))
         {
            $array[$key] = $this->processArray($value);
         }
      }
      // If the property isn't an object or array, leave it untouched
      return($array);
   }

	// Return a JSON string which represents this object by default
	public function __toString()
	{
		return($this->getJson());
	}

	// Return a JSON string which represents this object
	public function getJson()
	{
		return(json_encode($this->toArray()));
	}
}