<?php

final class PhabricatorUserSearchIndexer
  extends PhabricatorSearchDocumentIndexer {

  public function getIndexableObject() {
    return new PhabricatorUser();
  }

  protected function buildAbstractDocumentByPHID($phid) {
    $user = $this->loadDocumentByPHID($phid);

    $doc = new PhabricatorSearchAbstractDocument();
    $doc->setPHID($user->getPHID());
    $doc->setDocumentType(PhabricatorPeoplePHIDTypeUser::TYPECONST);
    $doc->setDocumentTitle($user->getUserName().' ('.$user->getRealName().')');
    $doc->setDocumentCreated($user->getDateCreated());
    $doc->setDocumentModified($user->getDateModified());

    // TODO: Index the blurbs from their profile or something? Probably not
    // actually useful...

    $doc->addRelationship(
      $user->isUserActivated()
        ? PhabricatorSearchRelationship::RELATIONSHIP_OPEN
        : PhabricatorSearchRelationship::RELATIONSHIP_CLOSED,
      $user->getPHID(),
      PhabricatorPeoplePHIDTypeUser::TYPECONST,
      time());

    return $doc;
  }
}
