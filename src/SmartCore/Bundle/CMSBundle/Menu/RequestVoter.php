<?php

namespace SmartCore\Bundle\CMSBundle\Menu;

use Knp\Menu\ItemInterface;
use Knp\Menu\Matcher\Voter\VoterInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class RequestVoter implements VoterInterface
{
    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param ItemInterface $item
     * @return bool|null
     */
    public function matchItem(ItemInterface $item)
    {
        $request = $this->requestStack->getCurrentRequest();

        $parent = $item->getParent();

        while (null !== $parent->getParent()) {
            $parent = $parent->getParent();
        }

        if ($item->getUri() === $request->getRequestUri() or
            $item->getUri() === $request->attributes->get('__current_folder_path', false)
        ) {
            // URL's completely match
            return true;
        } elseif (
            $item->getUri() !== $request->getBaseUrl().'/' and
            $item->getUri() === substr($request->getRequestUri(), 0, strlen($item->getUri())) and
            $request->attributes->get('__selected_inheritance', true) and
            $parent->getExtra('select_intehitance', true)
        ) {
            // URL isn't just "/" and the first part of the URL match
            return true;
        }

        return false;
    }
}
