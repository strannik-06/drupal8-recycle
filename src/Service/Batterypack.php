<?php
namespace Drupal\recycle\Service;

use Drupal\Core\Database\Connection;

/**
 * Service for batterypack entity.
 */
class Batterypack
{
    /**
     * @var Connection
     */
    protected $database;

    /**
     * @param Connection $database
     */
    public function __construct(Connection $database)
    {
        $this->database = $database;
    }

    /**
     * Save an entry in the database.
     *
     * @param array $entry
    *
     * @return int
     * @throws \Exception
     */
    public static function insert($entry)
    {
        $returnValue = NULL;
        try {
            $returnValue = db_insert('recycle_batterypack')
                ->fields($entry)
                ->execute();
        }
        catch (\Exception $e) {
            drupal_set_message(t('db_insert failed. Message = %message, query= %query', array(
                '%message' => $e->getMessage(),
                '%query' => $e->query_string,
            )), 'error');
        }

        return $returnValue;
    }

    /**
     * @return mixed
     */
    public function getAllGroupedByType()
    {
        $query = $this->database->select('recycle_batterypack', 'r');
        $query->addExpression('SUM(r.amount)',  'total_amount');

        return $query
            ->fields('r', array('type'))
            ->groupBy('r.type')
            ->execute()
            ->fetchAllAssoc('type');
    }
}
