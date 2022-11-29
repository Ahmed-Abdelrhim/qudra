<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Pricing\V2;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceContext;
use Twilio\Options;
use Twilio\Values;
use Twilio\Version;

class NumberContext extends InstanceContext {
    /**
     * Initialize the NumberContext
     *
     * @param Version $version Version that contains the resource
     * @param string $destinationNumber The destination number for which to fetch
     *                                  pricing information
     */
    public function __construct(Version $version, $destinationNumber) {
        parent::__construct($version);

        // Path Solution
        $this->solution = ['destinationNumber' => $destinationNumber, ];

        $this->uri = '/Trunking/Numbers/' . \rawurlencode($destinationNumber) . '';
    }

    /**
     * Fetch the NumberInstance
     *
     * @param array|Options $options Optional Arguments
     * @return NumberInstance Fetched NumberInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch(array $options = []): NumberInstance {
        $options = new Values($options);

        $params = Values::of(['OriginationNumber' => $options['originationNumber'], ]);

        $payload = $this->version->fetch('GET', $this->uri, $params);

        return new NumberInstance($this->version, $payload, $this->solution['destinationNumber']);
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string {
        $context = [];
        foreach ($this->solution as $key => $value) {
            $context[] = "$key=$value";
        }
        return '[Twilio.Pricing.V2.NumberContext ' . \implode(' ', $context) . ']';
    }
}