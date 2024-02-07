<?php

declare (strict_types=1);
namespace Rector\Doctrine\CodeQuality\AnnotationTransformer\PropertyAnnotationTransformer;

use PhpParser\Node\Stmt\Property;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\Doctrine\CodeQuality\Contract\PropertyAnnotationTransformerInterface;
use Rector\Doctrine\CodeQuality\DocTagNodeFactory;
use Rector\Doctrine\CodeQuality\NodeFactory\ArrayItemNodeFactory;
use Rector\Doctrine\CodeQuality\ValueObject\EntityMapping;
final class IdGeneratorAnnotationTransformer implements PropertyAnnotationTransformerInterface
{
    /**
     * @readonly
     * @var \Rector\Doctrine\CodeQuality\NodeFactory\ArrayItemNodeFactory
     */
    private $arrayItemNodeFactory;
    public function __construct(ArrayItemNodeFactory $arrayItemNodeFactory)
    {
        $this->arrayItemNodeFactory = $arrayItemNodeFactory;
    }
    public function transform(EntityMapping $entityMapping, PhpDocInfo $propertyPhpDocInfo, Property $property) : void
    {
        $idMapping = $entityMapping->matchIdPropertyMapping($property);
        if (!\is_array($idMapping)) {
            return;
        }
        $generator = $idMapping['generator'] ?? null;
        if (!\is_array($generator)) {
            return;
        }
        $arrayItemNodes = $this->arrayItemNodeFactory->create($generator, ['strategy']);
        $spacelessPhpDocTagNode = DocTagNodeFactory::createSpacelessPhpDocTagNode($arrayItemNodes, $this->getClassName());
        $propertyPhpDocInfo->addPhpDocTagNode($spacelessPhpDocTagNode);
    }
    public function getClassName() : string
    {
        return 'Doctrine\\ORM\\Mapping\\GeneratedValue';
    }
}
