<?php

namespace Platform\SampleContainer\Handlers\Commands;

use Illuminate\Support\Facades\Auth;
use Platform\App\Commanding\CommandHandler;
use Platform\App\Exceptions\SeException;
use Platform\SampleContainer\Repositories\Contracts\SampleCriteriaCommentRepository;

class DeleteSampleCriteriaCommentCommandHandler implements CommandHandler
{
    /**
     * The sampleCriteriaComment repositiry
     * @var string
     */
    private $criteriaComment;

    /**
     * Construct the handler
     * @param SampleCriteriaCommentRepository $sample
     */
    public function __construct(SampleCriteriaCommentRepository $criteriaComment)
    {
        $this->criteriaComment = $criteriaComment;
    }

    /**
     * Handle the DeleteSampleCriteriaCommentCommand
     * @param  DeleteSampleCriteriaCommentCommand $command
     * @return mixed
     */
    public function handle($command)
    {
        $criteriaComment = $this->criteriaComment->getByCriteriaIdAndCommentId(
            $command->criteriaId,
            $command->commentId
        );

        if ($criteriaComment) {
            $this->isAuthor($criteriaComment, Auth::user());
            return $criteriaComment->delete();
        }
        throw new SeException("Comment not found.", 404);
    }

    /**
     * Check if authenticated user is the author of the comment
     * @param  Object  $criteriaComment
     * @param  Object  $user
     * @return boolean
     */
    public function isAuthor($criteriaComment, $user)
    {
        if ($criteriaComment->commenter_id == $user->id) {
            return true;
        }
        throw new SeException(
            "You must be the author of the comment to delete it.",
            401
        );
    }
}