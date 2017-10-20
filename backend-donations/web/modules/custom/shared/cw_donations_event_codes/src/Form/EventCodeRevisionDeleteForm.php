<?php

namespace Drupal\cw_donations_event_codes\Form;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for deleting a Event code revision.
 *
 * @ingroup cw_donations_event_codes
 */
class EventCodeRevisionDeleteForm extends ConfirmFormBase {


  /**
   * The Event code revision.
   *
   * @var \Drupal\cw_donations_event_codes\Entity\EventCodeInterface
   */
  protected $revision;

  /**
   * The Event code storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $EventCodeStorage;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * Constructs a new EventCodeRevisionDeleteForm.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $entity_storage
   *   The entity storage.
   * @param \Drupal\Core\Database\Connection $connection
   *   The database connection.
   */
  public function __construct(EntityStorageInterface $entity_storage, Connection $connection) {
    $this->EventCodeStorage = $entity_storage;
    $this->connection = $connection;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $entity_manager = $container->get('entity.manager');
    return new static(
      $entity_manager->getStorage('event_code'),
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'event_code_revision_delete_confirm';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return t('Are you sure you want to delete the revision from %revision-date?', array('%revision-date' => format_date($this->revision->getRevisionCreationTime())));
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.event_code.version_history', array('event_code' => $this->revision->id()));
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $event_code_revision = NULL) {
    $this->revision = $this->EventCodeStorage->loadRevision($event_code_revision);
    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->EventCodeStorage->deleteRevision($this->revision->getRevisionId());

    $this->logger('content')->notice('Event code: deleted %title revision %revision.', array('%title' => $this->revision->label(), '%revision' => $this->revision->getRevisionId()));
    drupal_set_message(t('Revision from %revision-date of Event code %title has been deleted.', array('%revision-date' => format_date($this->revision->getRevisionCreationTime()), '%title' => $this->revision->label())));
    $form_state->setRedirect(
      'entity.event_code.canonical',
       array('event_code' => $this->revision->id())
    );
    if ($this->connection->query('SELECT COUNT(DISTINCT vid) FROM {event_code_field_revision} WHERE id = :id', array(':id' => $this->revision->id()))->fetchField() > 1) {
      $form_state->setRedirect(
        'entity.event_code.version_history',
         array('event_code' => $this->revision->id())
      );
    }
  }

}
