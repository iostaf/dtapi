<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class with definitions Question table model
 *
 */

class Model_Question extends Model_Common {
	
	protected $tableName = "questions";
	protected $fieldNames = array("question_id", "test_id", "question_text", "level", "type", "attachment");
	
	/**
	 * 
	 * @param int $test_id
	 * @param int $level
	 * @param int $number
	 * @return MySQL Result Set
	 * @deprecated should be deprecated due to a lot of data
	 */
	public function getQuestionsByLevelRand($test_id, $level, $number)
	{
		$query = DB::select_array($this->fieldNames)->from($this->tableName)
				->where($this->fieldNames[1], "=", $test_id)
				->and_where($this->fieldNames[3], "=", $level)
				->order_by("", 'RAND()')
				->limit($number);
		$result = $query->as_object()->execute();
		return $result;
	}
	
	/**
	 *
	 * @param int $test_id
	 * @param int $level
	 * @param int $number
	 * @return MySQL Result Set
	 */
	public function getQuestionIdsByLevelRand($test_id, $level, $number)
	{
		$query = DB::select($this->fieldNames[0])->from($this->tableName)
		->where($this->fieldNames[1], "=", $test_id)
		->and_where($this->fieldNames[3], "=", $level)
		->order_by("", 'RAND()')
		->limit($number);
		$result = $query->as_object()->execute();
		return $result;
	}
	
	/**
	 * 
	 * @param int $test_id
	 * @return int $count - number of records
	 */
	public function countQuestionsByTest($test_id)
	{
		$query = "SELECT COUNT(*) AS count FROM {$this->tableName} WHERE {$this->fieldNames[1]} = {$test_id}";
		$count = DB::query(Database::SELECT, $query)->execute()->get("count");
		return $count;
	}
	
	/**
	 * 
	 * @param int $question_id
	 * @return int $test_id - test_id which is using together with TestDetail when calculating the mark
	 */
	public function getTestIdByQuestion($question_id)
	{
		$query = "SELECT {$this->fieldNames[1]} AS id FROM {$this->tableName} WHERE {$this->fieldNames[0]} = {$question_id}";
		$test_id = DB::query(Database::SELECT, $query)->execute()->get("id");
		return $test_id;
	}
	
	/**
	 *
	 * @param int $question_id
	 * @return int $level - level_id which is using together with TestDetail when calculating the mark
	 */
	public function getLevelIdByQuestion($question_id)
	{
		$query = "SELECT {$this->fieldNames[3]} AS id FROM {$this->tableName} WHERE {$this->fieldNames[0]} = {$question_id}";
		$level_id = DB::query(Database::SELECT, $query)->execute()->get("id");
		return $level_id;
	}
	
	/**
	 * 
	 * @param int $test_id
	 * @param int $limit
	 * @param int $offset
	 * @return MySQL Result Set
	 */
	public function getQuestionsRangeByTest($test_id, $limit, $offset)
	{
		$query = DB::select_array($this->fieldNames)
				->from($this->tableName)
				->where($this->fieldNames[1], "=", $test_id)
				->order_by($this->fieldNames[0], 'asc')
				->limit($limit)
				->offset($offset);
		$result = $query->as_object()->execute();
		return $result;
	}
	
	/**
	 * 
	 * @param int $question_id
	 * @return int question type
	 * 1 - QTYPE_SIMPLE_CHOICE
	 * 2 - QTYPE_MULTI_CHOICE
	 * 3 - QTYPE_INPUT_FIELD
	 */
	public function getQuestionTypeById($question_id)
	{
		$query = DB::select($this->fieldNames[4])
				->from($this->tableName)
				->where($this->fieldNames[0], "=", $question_id);
		$result = $query->as_object()->execute();
		foreach ($result as $question)
		{
			return $question->type;
		}
	}
	
}
