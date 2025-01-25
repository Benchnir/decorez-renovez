<?php

/**
 * Description of CommentaireController
 *
 * @author Lordinaire
 */
class Admin_CommentaireController extends My_Controller_Action
{
    public function init()
    {
        parent::init();
        $this->_acl->allow('admin', $this->getResourceId(), 'index');
    }

    public function indexAction()
    {
        $this->checkUser();

        $type = My_Controller_Action::COMMENT_STATUS_NEW;
        if ($this->getRequest()->getParam('type') != null)
            $type = $this->getRequest()->getParam('type');
        
        $qb = $this->_em->createQueryBuilder();
        $qb->select('c')
                ->from('\Default_Model_Base_Comment', 'c')
                ->andWhere('c.status = :status')
                ->orderBy('c.created_at', 'DESC')
                ->setParameter('status', $type);

        $query = $qb->getQuery();
        $res = $query->getResult();

        $comments = Zend_Paginator::factory($res);
        $comments->setCurrentPageNumber($this->_getParam('page', 1));
        $comments->setItemCountPerPage($this->_getParam('resultat', 10));

        $this->view->type = $type;
        $this->view->comments = $comments;
    }

    /*
     * AJAX REQUEST
     */

    /**
     * Valider le commentaire
     */
    public function validerAction()
    {
        if ($this->_admin == null)
            return;

        if ($this->getRequest()->isXmlHttpRequest())
        {
            $this->_helper->viewRenderer->setNoRender();
            $this->_helper->layout()->disableLayout();

            if ($this->_request->isPost())
            {
                $id_comment = intval($_POST['id_comment']);
                if ($id_comment <= 0)
                    return;

                $qb = $this->_em->createQueryBuilder();
                $qb->add('select', 'c')
                        ->add('from', '\Default_Model_Base_Comment c')
                        ->add('where', 'c.id = :id');
                $query = $qb->getQuery();
                $query->setParameter('id', $id_comment);

                try
                {
                    $comment = $query->getSingleResult();
                }
                catch (\Doctrine\ORM\NoResultException $e)
                {
                    return;
                }

                $member = $this->_em->find('\Default_Model_Base_Member', $this->_user->getId());
                $comment->setStatus(My_Controller_Action::COMMENT_STATUS_ACTIVE);
                $comment->setValidationDate(new \DateTime("now"));
                $comment->setValidator($member);
                $this->_em->merge($comment);
                $this->_em->flush();

                echo '0';
            }
        }
    }

    /**
     * Refuser l'exposé
     */
    public function refuserAction()
    {
        if ($this->_admin == null)
            return;

        if ($this->getRequest()->isXmlHttpRequest())
        {
            $this->_helper->viewRenderer->setNoRender();
            $this->_helper->layout()->disableLayout();

            if ($this->_request->isPost())
            {
                $id_comment = intval($_POST['id_comment']);
                $reason = $_POST['reason'];
                if ($id_comment <= 0 && empty($reason))
                    return;

                $qb = $this->_em->createQueryBuilder();
                $qb->add('select', 'c')
                        ->add('from', '\Default_Model_Base_Comment c')
                        ->add('where', 'c.id = :id');
                $query = $qb->getQuery();
                $query->setParameter('id', $id_comment);

                try
                {
                    $comment = $query->getSingleResult();
                }
                catch (\Doctrine\ORM\NoResultException $e)
                {
                    return;
                }
                $member = $this->_em->find('\Default_Model_Base_Member', $this->_user->getId());
                $comment->setStatus(My_Controller_Action::COMMENT_STATUS_MODERATED);
                $comment->setReason($reason);
                $comment->setValidationDate(new \DateTime("now"));
                $comment->setValidator($member);
                $this->_em->merge($comment);
                $this->_em->flush();

                echo '0';
            }
        }
    }

    public function __toString()
    {
        return $this->_result;
    }

    public function getResourceId() {
        return 'adminCommentaireController';
    }
}