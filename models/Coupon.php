<?php
class Coupon
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findCouponByCode($code)
    {
        $this->db->query('SELECT * FROM coupons WHERE code = :code AND status = "active"');
        $this->db->bind(':code', $code);
        return $this->db->single();
    }

    public function getAvailableCoupons()
    {
        $this->db->query('SELECT * FROM coupons WHERE status = "active"');
        return $this->db->resultSet();
    }
}
