<?php
  //--------------------------------------------------------------------------
  // PHPcoord
  // phpcoord.php
  //
  // (c) 2005 Jonathan Stott
  //
  // Created on 11-Aug-2005
  //
  // 2.3 - 24 Aug 2006
  //  - Changed OSRef->toSixFigureString() so that the eastings and northings
  //    are rounded rather than floored.
  // 2.2 - 11 Feb 2006
  //  - Used different algorithm for calculating distance between latitudes
  //    and longitudes - fixes a number of problems with distance calculations
  // 2.1 - 22 Dec 2005
  //  - Added getOSRefFromSixFigureReference function
  // 2.0 - 21 Dec 2005
  //  - Completely different object design - conversion functions now through
  //    objects rather than static functions
  //  - Updated comments and documentation
  // 1.1 - 11 Sep 2005
  //  - Added OSGB36/WGS84 data conversions
  // 1.0 - 11 Aug 2005
  //  - Initial version
  //--------------------------------------------------------------------------

  class RefEll {

    public $maj;
    public $min;
    public $ecc;


    /**
     * Create a new RefEll object to represent a reference ellipsoid
     *
     * @param maj the major axis
     * @param min the minor axis
     */
    function __construct($maj, $min) {
      $this->maj = $maj;
      $this->min = $min;
      $this->ecc = (($maj * $maj) - ($min * $min)) / ($maj * $maj);
    }
  }
?>