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
 * CForumThread.
 *
 * @ORM\Table(
 *     name="c_forum_thread",
 *     indexes={
 *     }
 * )
 * @ORM\Entity(repositoryClass="Chamilo\CourseBundle\Repository\CForumThreadRepository")
 */
class CForumThread extends AbstractResource implements ResourceInterface
{
    /**
     * @ORM\Column(name="iid", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected int $iid;

    /**
     * @ORM\Column(name="thread_title", type="string", length=255, nullable=false)
     */
    #[Assert\NotBlank]
    protected string $threadTitle;

    /**
     * @ORM\ManyToOne(targetEntity="Chamilo\CourseBundle\Entity\CForum", inversedBy="threads")
     * @ORM\JoinColumn(name="forum_id", referencedColumnName="iid", nullable=true, onDelete="CASCADE")
     */
    protected ?CForum $forum = null;

    /**
     * @ORM\ManyToOne(targetEntity="Chamilo\CoreBundle\Entity\User")
     * @ORM\JoinColumn(name="thread_poster_id", referencedColumnName="id")
     */
    protected User $user;

    /**
     * @ORM\ManyToOne(targetEntity="Chamilo\CourseBundle\Entity\CForumPost", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="thread_last_post", referencedColumnName="iid", onDelete="SET NULL")
     */
    protected ?CForumPost $threadLastPost = null;

    /**
     * @ORM\ManyToOne(targetEntity="Chamilo\CourseBundle\Entity\CLpItem")
     * @ORM\JoinColumn(name="lp_item_id", referencedColumnName="iid", onDelete="CASCADE")
     */
    protected ?CLpItem $item = null;

    /**
     * @var Collection|CForumPost[]
     *
     * @ORM\OneToMany(targetEntity="CForumPost", mappedBy="thread", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    protected Collection $posts;

    /**
     * @var Collection|CForumThreadQualify[]
     *
     * @ORM\OneToMany(targetEntity="CForumThreadQualify", mappedBy="thread", cascade={"persist", "remove"})
     */
    protected Collection $qualifications;

    /**
     * @ORM\Column(name="thread_date", type="datetime", nullable=false)
     */
    #[Assert\NotBlank]
    protected DateTime $threadDate;

    /**
     * @ORM\Column(name="thread_replies", type="integer", nullable=false, options={"unsigned":true, "default":0})
     */
    #[Assert\NotBlank]
    protected int $threadReplies;

    /**
     * @ORM\Column(name="thread_views", type="integer", nullable=false, options={"unsigned":true, "default":0})
     */
    #[Assert\NotBlank]
    protected int $threadViews;

    /**
     * @ORM\Column(name="thread_sticky", type="boolean", nullable=false)
     */
    #[Assert\NotNull]
    protected bool $threadSticky;

    /**
     * @ORM\Column(name="locked", type="integer", nullable=false)
     */
    #[Assert\NotBlank]
    protected int $locked;

    /**
     * @ORM\Column(name="thread_title_qualify", type="string", length=255, nullable=true)
     */
    protected ?string $threadTitleQualify = null;

    /**
     * @ORM\Column(name="thread_qualify_max", type="float", precision=6, scale=2, nullable=false)
     */
    protected float $threadQualifyMax;

    /**
     * @ORM\Column(name="thread_close_date", type="datetime", nullable=true)
     */
    protected ?DateTime $threadCloseDate = null;

    /**
     * @ORM\Column(name="thread_weight", type="float", precision=6, scale=2, nullable=false)
     */
    protected float $threadWeight;

    /**
     * @ORM\Column(name="thread_peer_qualify", type="boolean")
     */
    protected bool $threadPeerQualify;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->qualifications = new ArrayCollection();
        $this->threadDate = new DateTime();
        $this->threadPeerQualify = false;
        $this->threadReplies = 0;
        $this->threadViews = 0;
        $this->locked = 0;
        $this->threadQualifyMax = 0;
        $this->threadWeight = 0;
        $this->threadSticky = false;
    }

    public function __toString(): string
    {
        return $this->getThreadTitle();
    }

    public function isThreadPeerQualify(): bool
    {
        return $this->threadPeerQualify;
    }

    public function setThreadPeerQualify(bool $threadPeerQualify): self
    {
        $this->threadPeerQualify = $threadPeerQualify;

        return $this;
    }

    public function setThreadTitle(string $threadTitle): self
    {
        $this->threadTitle = $threadTitle;

        return $this;
    }

    public function getThreadTitle(): string
    {
        return $this->threadTitle;
    }

    public function setForum(CForum $forum = null): self
    {
        if (null !== $forum) {
            $forum->getThreads()->add($this);
        }
        $this->forum = $forum;

        return $this;
    }

    public function getForum(): ?CForum
    {
        return $this->forum;
    }

    public function setThreadReplies(int $threadReplies): self
    {
        $this->threadReplies = $threadReplies;

        return $this;
    }

    public function getThreadReplies(): int
    {
        return $this->threadReplies;
    }

    public function setThreadViews(int $threadViews): self
    {
        $this->threadViews = $threadViews;

        return $this;
    }

    public function getThreadViews(): int
    {
        return $this->threadViews;
    }

    public function setThreadDate(DateTime $threadDate): self
    {
        $this->threadDate = $threadDate;

        return $this;
    }

    /**
     * Get threadDate.
     *
     * @return DateTime
     */
    public function getThreadDate()
    {
        return $this->threadDate;
    }

    public function setThreadSticky(bool $threadSticky): self
    {
        $this->threadSticky = $threadSticky;

        return $this;
    }

    /**
     * Get threadSticky.
     *
     * @return bool
     */
    public function getThreadSticky()
    {
        return $this->threadSticky;
    }

    public function setLocked(int $locked): self
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * Get locked.
     *
     * @return int
     */
    public function getLocked()
    {
        return $this->locked;
    }

    public function setThreadTitleQualify(string $threadTitleQualify): self
    {
        $this->threadTitleQualify = $threadTitleQualify;

        return $this;
    }

    /**
     * Get threadTitleQualify.
     *
     * @return string
     */
    public function getThreadTitleQualify()
    {
        return $this->threadTitleQualify;
    }

    public function setThreadQualifyMax(float $threadQualifyMax): self
    {
        $this->threadQualifyMax = $threadQualifyMax;

        return $this;
    }

    /**
     * Get threadQualifyMax.
     *
     * @return float
     */
    public function getThreadQualifyMax()
    {
        return $this->threadQualifyMax;
    }

    public function setThreadCloseDate(DateTime $threadCloseDate): self
    {
        $this->threadCloseDate = $threadCloseDate;

        return $this;
    }

    /**
     * Get threadCloseDate.
     *
     * @return DateTime
     */
    public function getThreadCloseDate()
    {
        return $this->threadCloseDate;
    }

    public function setThreadWeight(float $threadWeight): self
    {
        $this->threadWeight = $threadWeight;

        return $this;
    }

    /**
     * Get threadWeight.
     *
     * @return float
     */
    public function getThreadWeight()
    {
        return $this->threadWeight;
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

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|CForumPost[]
     */
    public function getPosts()
    {
        return $this->posts;
    }

    public function getThreadLastPost(): ?CForumPost
    {
        return $this->threadLastPost;
    }

    public function setThreadLastPost(CForumPost $threadLastPost): self
    {
        $this->threadLastPost = $threadLastPost;

        return $this;
    }

    /**
     * @return CForumThreadQualify[]|Collection
     */
    public function getQualifications()
    {
        return $this->qualifications;
    }

    /**
     * @param CForumThreadQualify[]|Collection $qualifications
     */
    public function setQualifications(Collection $qualifications): self
    {
        $this->qualifications = $qualifications;

        return $this;
    }

    public function getItem(): ?CLpItem
    {
        return $this->item;
    }

    public function setItem(?CLpItem $item): self
    {
        $this->item = $item;

        return $this;
    }

    public function getResourceIdentifier(): int
    {
        return $this->getIid();
    }

    public function getResourceName(): string
    {
        return $this->getThreadTitle();
    }

    public function setResourceName(string $name): self
    {
        return $this->setThreadTitle($name);
    }
}
