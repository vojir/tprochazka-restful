<?php
namespace Drahak\Restful\Tools\Documentation\Spies;

use Drahak\Restful\Input;
use Drahak\Restful\Validation\IField;
use Drahak\Restful\Validation\IValidator;
use Drahak\Restful\Validation\Rule;

/**
 * Input
 * @package Drahak\Restful\Tools\Documentation
 * @author Drahomír Hanák
 */
final class InputSpy extends Input
{

	/** @var array */
	private $accessedFields = array();

	/** @var array */
	public static $ruleDescription = array(
		IValidator::EMAIL => 'an valid email address',
		IValidator::URL => 'an valid URL address',
		IValidator::FLOAT => 'a float',
		IValidator::INTEGER => 'an integer',
		IValidator::LENGTH => 'of length from %d to %d',
		IValidator::MIN_LENGTH => 'of minimal length %d',
		IValidator::MAX_LENGTH => 'of maximal length %d',
		IValidator::RANGE => 'in range from %d to %d',
		IValidator::PATTERN => 'matches %s',
		IValidator::IS_IN => 'one of given values',
	);

	/** @var array */
	public static $ruleTypes = array(
		IValidator::EMAIL => 'string',
		IValidator::URL => 'string',
		IValidator::FLOAT => 'float',
		IValidator::INTEGER => 'integer',
		IValidator::LENGTH => 'string',
		IValidator::MIN_LENGTH => 'string',
		IValidator::MAX_LENGTH => 'string',
		IValidator::RANGE => 'number',
		IValidator::PATTERN => NULL,
		IValidator::IS_IN => NULL,
	);

	/**
	 * @param string $name
	 * @return mixed
	 */
	public function &__get($name)
	{
		$value = parent::__get($name);
		if (!property_exists($this, $name)) {
			$this->addAccessedField($name);
		}
		return $value;
	}

	/**
	 * @param string $name
	 * @return IField
	 */
	public function field($name)
	{
		$field = parent::field($name);
		$this->addAccessedField($name);
		return $field;
	}

	/**
	 * @param string $name
	 */
	private function addAccessedField($name)
	{
		$this->accessedFields[] = $name;
	}

	/**
	 * Get accessed fields
	 * @return array
	 */
	final public function getAccessedFields()
	{
		return $this->accessedFields;
	}

	/**
	 * Get field rules
	 * @param string $name
	 * @return array
	 */
	public function getParameter($name)
	{
		$field = $this->field($name);
		$rules = $field ? $field->getRules() : array();
		$description = 'Value must be ';
		$type = NULL;

		/** @var Rule $rule */
		foreach ($rules as $i => $rule) {
			$type = isset(self::$ruleTypes[$rule->expression]) ? self::$ruleTypes[$rule->expression] : $type;
			$description .= $i === 0 ? '' : ', ';
			$description .= isset(self::$ruleDescription[$rule->expression]) ?
				vsprintf(self::$ruleDescription[$rule->expression], $rule->argument) :
				'';
		}

		return array(
			'name' => $name,
			'description' => $description,
			'optional' => FALSE,
			'type' => $type
		);
	}

}