<?php
namespace SmartCore\Bundle\SessionBundle\Handler;

use Doctrine\ORM\EntityManager;
use SmartCore\Bundle\SessionBundle\Entity\Session;

class Orm implements \SessionHandlerInterface
{
    /**
     * @var \PDO PDO instance.
     */
    protected $em;

    /**
     * @var int
     */
    protected $user_id;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->user_id = 0;
    }

    /**
     * {@inheritDoc}
     */
    public function open($path, $name)
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function close()
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function destroy($id)
    {
        $session = $this->em->find('SmartCoreSessionBundle:Session', $id);
        
        if (is_object($session)) {
            $this->em->remove($session);
            $this->em->flush();
        }        
        
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function gc($lifetime)
    {
        $now = new \DateTime();
        $this->em
            ->createQuery('DELETE SmartCoreSessionBundle:Session s WHERE s.time < :time')
            ->setParameter('time', $now->modify("-$lifetime seconds"))
            ->execute();

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function read($id)
    {
        /** @var Session $session */
        $session = $this->em->find('SmartCoreSessionBundle:Session', $id);
        
        if (is_object($session)) {
            return $session->getData();
        }
        
        $this->createNewSession($id);
        return '';
    }

    /**
     * {@inheritDoc}
     */
    public function write($id, $data)
    {
        /** @var Session $session */
        $session = $this->em->find('SmartCoreSessionBundle:Session', $id);
        
        if (is_object($session)) {
            $session->setData($data);
            $session->setTime(new \DateTime());
            $session->setUserId($this->user_id);
            
            $this->em->persist($session);
            $this->em->flush();
        } else {
            $this->createNewSession($id, $data);
        }
        
        return true;
    }

    /**
     * Creates a new session with the given $id and $data
     *
     * @param string $id
     * @param string $data
     *
     * @return boolean True.
     */
    protected function createNewSession($id, $data = '')
    {
        $session = new Session();
        $session->setId($id);
        $session->setData($data);
        
        $this->em->persist($session);
        $this->em->flush();
        
        return true;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setUserId($id)
    {
        $this->user_id = $id;

        return $this;
    }
}
