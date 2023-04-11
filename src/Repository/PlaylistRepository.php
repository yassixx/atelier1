<?php

namespace App\Repository;

use App\Entity\Playlist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

define("P_ID_ID", "p.id id");
define("P_NAME_NAME", "p.name name");
define("C_NAME", "c.name");
define("P_FORMATIONS", "p.formations");
define("C_NAME_CATEGORIENAME", "c.name categoriename");
define("F_CATEGORIES", "f.categories");

/**
 * @extends ServiceEntityRepository<Playlist>
 *
 * @method Playlist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Playlist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Playlist[]    findAll()
 * @method Playlist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlaylistRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Playlist::class);
    }

    public function add(Playlist $entity, bool $flush = false): void {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Playlist $entity, bool $flush = false): void {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Retourne toutes les playlists triées sur le nom de la playlist
     * @param type $ordre
     * @return Playlist[]
     */
    public function findAllOrderByName($ordre): array {
        return $this->createQueryBuilder('p')                       
                        ->leftjoin(P_FORMATIONS, 'f')
                        ->groupBy('p.id')
                        ->orderBy('p.name', $ordre)
                        ->getQuery()
                        ->getResult();
    }

    /**
     * Enregistrements dont un champ contient une valeur
     * ou tous les enregistrements si la valeur est vide
     * @param type $champ
     * @param type $valeur
     * @return Playlist[]
     */
    public function findByContainValue($champ, $valeur): array {
        if ($valeur == "") {
            return $this->findAllOrderByName('ASC');
        }
        return $this->createQueryBuilder('p')                      
                        ->leftjoin(P_FORMATIONS, 'f')                     
                        ->where('p.' . $champ . ' LIKE :valeur')
                        ->setParameter('valeur', '%' . $valeur . '%')
                        ->groupBy('p.id')
                        ->orderBy('p.name', 'ASC')
                        ->getQuery()
                        ->getResult();
    }

    /**
     * Enregistrements dont un champ contient une valeur
     * ou tous les enregistrements si la valeur est vide
     * et présence de table non vide donc champ dans une autre table
     * @param type $champ
     * @param type $valeur
     * @param type $table car $champ dans une autre table
     * @return Playlist[]
     */
    public function findByContainValueTable($champ, $valeur, $table): array {
        if ($valeur == "") {
            return $this->findAllOrderByName('ASC');
        }
        return $this->createQueryBuilder('p')                     
                        ->leftjoin(P_FORMATIONS, 'f')
                        ->leftjoin(F_CATEGORIES, 'c')
                        ->where('c.' . $champ . ' LIKE :valeur')
                        ->setParameter('valeur', '%' . $valeur . '%')
                        ->groupBy('p.id')
                        ->orderBy('p.name', 'ASC')
                        ->getQuery()
                        ->getResult();
    }
    
    /**
     * Retourne toutes les playlists triées sur le
     * nb de formations
     * @param type $ordre
     * @return Playlist[]
     */
    public function findAllOrderByNbFormations($ordre): array{
        return $this->createQueryBuilder('p')
                ->leftJoin(P_FORMATIONS, 'f')
                ->groupBy('p.id')
                ->orderBy('count(p.name)', $ordre)
                ->getQuery()
                ->getResult();
    }
    
    
}
