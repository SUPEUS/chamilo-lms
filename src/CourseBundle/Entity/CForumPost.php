<?php

declare(strict_types=1);

/* For licensing terms, see /license.txt */

namespace Chamilo\CourseBundle\Entity;

use Chamilo\CoreBundle\Entity\AbstractResource;
use Chamilo\CoreBundle\Entity\ResourceInterface;
use Chamilo\CoreBundle\Entity\User;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CForumPost.
 *
 * @ORM\Table(
 *     name="c_forum_post",
 *     indexes={
 *         @ORM\Index(name="forum_id", columns={"forum_id"}),
 *         @ORM\Index(name="idx_forum_post_thread_id", columns={"thread_id"}),
 *         @ORM\Index(name="idx_forum_post_visible", columns={"visible"}),
 *     }
 * )
 * @ORM\Entity(repositoryClass="Chamilo\CourseBundle\Repository\CForumPostRepository")
 */
class CForumPost extends AbstractResource implements ResourceInterface
{
    public const STATUS_VALIDATED = 1;
    public const STATUS_WAITING_MODERATION = 2;
    public const STATUS_REJECTED = 3;

    /**
     * @ORM\Column(name="iid", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected int $iid;

    /**
     * @ORM\Column(name="post_title", type="string", length=250, nullable=false)
     */
    #[Assert\NotBlank]
    protected string $postTitle;

    /**
     * @ORM\Column(name="post_text", type="text", nullable=true)
     */
    protected ?string $postText = null;

    /**
     * @ORM\Column(name="post_date", type="datetime", nullable=false)
     */
    #[Assert\NotBlank]
    protected DateTime $postDate;

    /**
     * @ORM\Column(name="post_notification", type="boolean", nullable=true)
     */
    protected ?bool $postNotification = null;

    /**
     * @ORM\Column(name="visible", type="boolean", nullable=false)
     */
    #[Assert\NotNull]
    protected bool $visible;

    /**
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    protected ?int $status = null;

    /**
     * @ORM\ManyToOne(targetEntity="Chamilo\CourseBundle\Entity\CForumThread", inversedBy="posts")
     * @ORM\JoinColumn(name="thread_id", referencedColumnName="iid", nullable=true, onDelete="SET NULL")
     */
    protected ?CForumThread $thread = null;

    /**
     * @ORM\ManyToOne(targetEntity="Chamilo\CourseBundle\Entity\CForum", inversedBy="posts")
     * @ORM\JoinColumn(name="forum_id", referencedColumnName="iid", nullable=true, onDelete="SET NULL")
     */
    protected ?CForum $forum = null;

    /**
     * @ORM\ManyToOne(targetEntity="Chamilo\CoreBundle\Entity\User")
     * @ORM\JoinColumn(name="poster_id", referencedColumnName="id")
     */
    #[Assert\NotBlank]
    protected ?User $user = null;

    /**
     * @ORM\ManyToOne(targetEntity="Chamilo\CourseBundle\Entity\CForumPost", inversedBy="children")
     * @ORM\JoinColumn(name="post_parent_id", referencedColumnName="iid", onDelete="SET NULL")
     */
    protected ?CForumPost $postParent = null;

    /**
     * @var Collection|CForumPost[]
     * @ORM\OneToMany(targetEntity="CForumPost", mappedBy="postParent")
     */
    protected Collection $children;

    /**
     * @var Collection|CForumAttachment[]
     *
     * @ORM\OneToMany(targetEntity="CForumAttachment", mappedBy="post", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected Collection $attachments;

    public function __construct()
    {
        $this->postDate = new DateTime();
        $this->visible = false;
        $this->attachments = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getPostTitle();
    }

    public function setPostTitle(string $postTitle): self
    {
        $this->postTitle = $postTitle;

        return $this;
    }

    public function getPostTitle(): string
    {
        return $this->postTitle;
    }

    public function setPostText(string $postText): self
    {
        $this->postText = $postText;

        return $this;
    }

    public function getPostText(): ?string
    {
        return $this->postText;
    }

    public function setThread(CForumThread $thread = null): self
    {
        if (null !== $thread) {
            $thread->getPosts()->add($this);
        }
        $this->thread = $thread;

        return $this;
    }

    public function getThread(): CForumThread
    {
        return $this->thread;
    }

    public function setPostDate(DateTime $postDate): self
    {
        $this->postDate = $postDate;

        return $this;
    }

    /**
     * Get postDate.
     *
     * @return DateTime
     */
    public function getPostDate()
    {
        return $this->postDate;
    }

    public function setPostNotification(bool $postNotification): self
    {
        $this->postNotification = $postNotification;

        return $this;
    }

    /**
     * Get postNotification.
     *
     * @return bool
     */
    public function getPostNotification()
    {
        return $this->postNotification;
    }

    public function setVisible(bool $visible): self
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible.
     *
     * @return bool
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get iid.
     *
     * @return int
     */
    public function getIid()
    {
        return $this->iid;
    }

    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    public function removeAttachment(CForumAttachment $attachment): void
    {
        $this->attachments->removeElement($attachment);
    }

    public function getForum(): ?CForum
    {
        return $this->forum;
    }

    public function setForum(?CForum $forum): self
    {
        $forum->getPosts()->add($this);

        $this->forum = $forum;

        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getPostParent(): ?self
    {
        return $this->postParent;
    }

    public function setPostParent(?self $postParent): self
    {
        $this->postParent = $postParent;

        return $this;
    }

    /**
     * @return CForumPost[]|Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param CForumPost[]|Collection $children
     */
    public function setChildren(Collection $children): self
    {
        $this->children = $children;

        return $this;
    }

    public function getResourceIdentifier(): int
    {
        return $this->getIid();
    }

    public function getResourceName(): string
    {
        return $this->getPostTitle();
    }

    public function setResourceName(string $name): self
    {
        return $this->setPostTitle($name);
    }
}
