<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: ai.proto

namespace Rpc;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 *FaceCheck 返回值
 *
 * Generated from protobuf message <code>rpc.CheckReply</code>
 */
class CheckReply extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>bool is_face = 1;</code>
     */
    private $is_face = false;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type bool $is_face
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Ai::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>bool is_face = 1;</code>
     * @return bool
     */
    public function getIsFace()
    {
        return $this->is_face;
    }

    /**
     * Generated from protobuf field <code>bool is_face = 1;</code>
     * @param bool $var
     * @return $this
     */
    public function setIsFace($var)
    {
        GPBUtil::checkBool($var);
        $this->is_face = $var;

        return $this;
    }

}

