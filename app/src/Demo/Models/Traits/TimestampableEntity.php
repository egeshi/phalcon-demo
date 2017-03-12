<?php
/**
 * Created by Antony Repin
 * Date: 03.03.2017
 * Time: 0:03
 */

namespace Demo\Models\Traits;

use Demo\Exception\ApplicationException;

trait TimestampableEntity
{
    /**
     * @return $this
     * @throws ApplicationException
     */
    public function setCreated()
    {
        $date = self::validDate();
        $this->created = $date;
        return $this;
    }

    /**
     * @return $this
     * @throws ApplicationException
     */
    public function setUpdated()
    {
        $date = self::validDate();
        $this->updated = $date;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param $date
     * @return \DateTime
     * @throws ApplicationException
     */
    private function validDate(\DateTime $date = null)
    {

        $format = "Y-m-d H:i:s";

        if (!is_null($date)) {
            if (is_a($date, "DateTime")) {
                return $date->format($format);
            }
            throw new ApplicationException("Wrong date datatype", 500);
        }

        $now = new \DateTime();
        return $now->format($format);

    }
}
