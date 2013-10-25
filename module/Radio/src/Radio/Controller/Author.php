<?php

namespace Radio\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Radio\Provider\EntityManager;

/**
 * @SWG\Resource(resourcePath="/author",basePath="/api")
 */
class Author extends BaseController {

    use EntityManager;

    public function createConverter() {
        return function($result) {
            $a = $result->toArray();
            $a['shows'] = array();
            foreach ($result->getShowAuthors() as $showAuthor) {
                $a['shows'][] = $showAuthor->getShow()->toArrayShort();
            }
            return $a;
        };
    }

    /**
     * @SWG\Api(
     *   path="/author",
     *   description="Function related to the authors of the radio shows",
     *   @SWG\Operation(
     *     method="GET",
     *     summary="List all active author"
     *   )
     * )
     */
    public function getList() {
        return $this->getEntityList("\Radio\Entity\Author", $this->createConverter());
    }

    /**
     * @SWG\Api(
     *   path="/author/{id}",
     *   @SWG\Operation(
     *     method="GET",
     *     summary="Return information about a specific author",
     *     @SWG\Parameters(
     *        @SWG\Parameter(
     *           name= "id",
     *           description="Id of the author",
     *           paramType="path",
     *           type="string"
     *        )
     *     )
     *   )
     * )
     */
    public function get($id) {
        return $this->getEntity("\Radio\Entity\Author", $id, $this->createConverter());
    }

    public function create($data) {
        // validation        
        if ( !isset($data['name']) || !isset($data['photo']) ||
        !isset($data['avatar']) || !isset($data['introduction']) ||
        !isset($data['user']) ) {
            $this->getResponse()->setStatusCode(400);
            return new JsonModel(array("error" => "Mandatory fields: name, photo, avatar, introduction, user."));
        }
        
        // validate user id
        // TODO: validate via DB
        if ( !is_numeric($data['user']) ) {
            $this->getResponse()->setStatusCode(400);
            return new JsonModel(array("error" => "user must be numeric."));
        }
        
        try {
            $author = new \Radio\Entity\Author();
            
            $author->setName($data['name']);
            $author->setPhoto($data['photo']);
            $author->setAvatar($data['avatar']);
            $author->setIntroduction($data['introduction']);
            $user = $this->getEntityManager()->find('Radio\Entity\User', $data['user']);
    	    $author->setUser($user);

    	    $this->getEntityManager()->persist($author);
    	    $this->getEntityManager()->flush();

    	    return new JsonModel(array("create"=>"success"));
        } catch (\Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }

    public function update($id, $data) {
        // validation        
        if ( !isset($data['name']) && !isset($data['photo']) &&
        !isset($data['avatar']) && !isset($data['introduction']) &&
        !isset($data['user']) ) {
            $this->getResponse()->setStatusCode(400);
            return new JsonModel(array("error" => "One of the following fields must exist: name, photo, avatar, introduction, user."));
        }
        
        // validate user id
        // TODO: validate via DB
        if ( isset($data['user']) && !is_numeric($data['user']) ) {
            $this->getResponse()->setStatusCode(400);
            return new JsonModel(array("error" => "user must be numeric."));
        }
        try {
            $author = $this->getEntityManager()->find('Radio\Entity\Author', $id);
            if (isset($data['name'])) {
                $author->setName($data['name']);
                $updated .= " Name: " . $data['name'];
            }
            if (isset($data['photo'])) {
                $author->setPhoto($data['photo']);
                $updated .= " Photo: " . $data['photo'];
            }
            if (isset($data['avatar'])) {
                $author->setAvatar($data['avatar']);
                $updated .= " Avatar: " . $data['avatar'];
            }
            if (isset($data['introduction'])) {
                $author->setIntroduction($data['introduction']);
                $updated .= " Introduction: " . $data['introduction'];
            }
            if (isset($data['user'])) {
                $user = $this->getEntityManager()->find('Radio\Entity\User', $data['user']);
                $author->setUser($user);
                $updated .= " User: " . $data['user'];
            }
        
            $this->getEntityManager()->flush();
            return new JsonModel(array("update"=>"success", "Updated values"=>$updated));
        } catch (\Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }        
    }

    public function delete($id) {
        // TODO: validate via DB
        try {
            $author = $this->getEntityManager()->find('Radio\Entity\Author', $id);
            $this->getEntityManager()->remove($author);
            $this->getEntityManager()->flush();
            
            return new JsonModel(array("delete"=>"success"));
        } catch (\Exception $ex) {
            $this->getResponse()->setStatusCode(500);
            return new JsonModel(array("error" => $ex->getMessage()));
        }
    }

}
