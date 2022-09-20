<?php

namespace Ibnab\Bundle\QuantityImproverBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;
use Oro\Bundle\MigrationBundle\Migration\Extension\NameGeneratorAwareInterface;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\MigrationBundle\Tools\DbIdentifierNameGenerator;
use Oro\Bundle\ProductBundle\Entity\Product;

class DropCustomFields implements
    Migration,
    OrderedMigrationInterface,
    NameGeneratorAwareInterface
{
    /**
     * @var DbIdentifierNameGenerator
     */
    protected $nameGenerator;

    /**
     * {@inheritdoc}
     */
    public function setNameGenerator(DbIdentifierNameGenerator $nameGenerator)
    {
        $this->nameGenerator = $nameGenerator;
    }
    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        try{
        //$queries->addQuery(new DropCustomFieldsQuery(Product::class, $this->nameGenerator));
        
        $queries->addQuery(new DropCustomFieldsEntityConfigValuesQuery(Product::class, 'quantityImproverIncrement', 'product'));
                
        }catch(Exception $e){
            
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 10;
    }
}
